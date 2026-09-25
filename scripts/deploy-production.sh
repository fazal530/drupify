#!/usr/bin/env bash

set -Eeuo pipefail

readonly PROJECT_ROOT="${HOME}/public_html"
readonly BACKUP_DIR="${HOME}/deployment-backups"
readonly COMPOSER_BIN="${HOME}/bin/composer"
readonly DRUSH_BIN="${PROJECT_ROOT}/vendor/bin/drush"

mkdir -p "${BACKUP_DIR}"
exec 9>"${HOME}/.drupify-production-deploy.lock"

if ! flock -n 9; then
  echo "Another production deployment is already running."
  exit 1
fi

readonly TIMESTAMP="$(date +%Y%m%d-%H%M%S)"

cd "${PROJECT_ROOT}"

maintenance_enabled=0
database_defaults_file=""
restore_site() {
  exit_code=$?
  if [[ -n "${database_defaults_file}" ]]; then
    rm -f "${database_defaults_file}"
  fi
  if [[ "${maintenance_enabled}" -eq 1 ]]; then
    "${DRUSH_BIN}" state:set system.maintenance_mode 0 --input-format=integer || true
    "${DRUSH_BIN}" cache:rebuild || true
  fi
  if [[ "${exit_code}" -ne 0 ]]; then
    echo "Deployment failed with exit code ${exit_code}. See the GitHub Actions log."
  fi
}
trap restore_site EXIT

echo "Deploying commit $(git rev-parse --short HEAD) at $(date -Is)"

if [[ -n "$(git status --porcelain --untracked-files=no)" ]]; then
  echo "Tracked production files contain local changes; deployment stopped."
  git status --short
  exit 1
fi

if [[ ! -x "${COMPOSER_BIN}" ]]; then
  echo "Composer is missing at ${COMPOSER_BIN}."
  exit 1
fi

"${COMPOSER_BIN}" install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --optimize-autoloader

database_defaults_file="$(mktemp)"
chmod 600 "${database_defaults_file}"
export DRUPIFY_DB_DEFAULTS_FILE="${database_defaults_file}"
database_name="$("${DRUSH_BIN}" php:eval '
  $db = \Drupal\Core\Database\Database::getConnectionInfo("default")["default"];
  $quote = static fn($value) => "\"" . addcslashes((string) $value, "\\\"") . "\"";
  $defaults = "[client]\n";
  $defaults .= "user=" . $quote($db["username"]) . "\n";
  $defaults .= "password=" . $quote($db["password"]) . "\n";
  $defaults .= "host=" . $quote($db["host"] ?? "localhost") . "\n";
  if (!empty($db["port"])) {
    $defaults .= "port=" . (int) $db["port"] . "\n";
  }
  file_put_contents(getenv("DRUPIFY_DB_DEFAULTS_FILE"), $defaults);
  echo $db["database"];
')"
readonly DATABASE_BACKUP="${BACKUP_DIR}/database-before-${TIMESTAMP}.sql.gz"
/usr/bin/mysqldump \
  --defaults-extra-file="${database_defaults_file}" \
  --single-transaction \
  --quick \
  --skip-lock-tables \
  --no-tablespaces \
  "${database_name}" | gzip -c > "${DATABASE_BACKUP}"
rm -f "${database_defaults_file}"
database_defaults_file=""

if ! update_status="$("${DRUSH_BIN}" updatedb:status 2>&1)"; then
  printf '%s\n' "${update_status}"
  echo "Unable to check database update status; deployment stopped before maintenance mode."
  exit 1
fi

if [[ "${update_status}" != *"No database updates required."* ]]; then
  printf '%s\n' "${update_status}"
  echo "Pending database updates cannot be automated because proc_open is disabled on this host."
  echo "Run /update.php manually, then retry this deployment."
  exit 1
fi

echo "No database updates are required."

"${DRUSH_BIN}" state:set system.maintenance_mode 1 --input-format=integer
maintenance_enabled=1
"${DRUSH_BIN}" cache:rebuild

"${DRUSH_BIN}" config:import -y

"${DRUSH_BIN}" state:set system.maintenance_mode 0 --input-format=integer
maintenance_enabled=0
"${DRUSH_BIN}" cache:rebuild

echo "Deployment completed successfully at $(date -Is)."
echo "Database backup: ${DATABASE_BACKUP}"
