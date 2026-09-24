# Drupify

Drupal 11 site configured for local development with DDEV.

## Requirements

- Docker
- DDEV
- Composer

## Fresh setup

```bash
git clone <repository-url> drupify
cd drupify
ddev start
ddev composer install
ddev drush config:import -y
ddev drush cache:rebuild
```

Open the local site with:

```bash
ddev launch
```

## Configuration workflow

Export active configuration after Drupal configuration changes:

```bash
ddev drush config:export -y
```

Import committed configuration:

```bash
ddev drush config:import -y
ddev drush cache:rebuild
```

## Database

Database dumps are intentionally excluded from Git because this repository may
be public. Transfer databases privately and import a local dump with:

```bash
ddev import-db --file=/path/to/database.sql.gz
```
