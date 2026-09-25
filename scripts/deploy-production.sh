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
restore_site() {
  exit_code=$?
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

readonly DATABASE_BACKUP="${BACKUP_DIR}/database-before-${TIMESTAMP}.sql"
"${DRUSH_BIN}" sql:dump --gzip --result-file="${DATABASE_BACKUP}"

"${DRUSH_BIN}" state:set system.maintenance_mode 1 --input-format=integer
maintenance_enabled=1
"${DRUSH_BIN}" cache:rebuild

"${DRUSH_BIN}" updatedb -y
"${DRUSH_BIN}" config:import -y

"${DRUSH_BIN}" state:set system.maintenance_mode 0 --input-format=integer
maintenance_enabled=0
"${DRUSH_BIN}" cache:rebuild

echo "Deployment completed successfully at $(date -Is)."
echo "Database backup: ${DATABASE_BACKUP}.gz"
