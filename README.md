# cstmd
Automatically exported from code.google.com/p/cstmd

## Running with Docker (XAMPP 5.6.3 inside the container)
- Prereqs: Docker + Docker Compose.
- Pull the ReCaptcha submodule once: `git submodule update --init --recursive` (needed for `common/lib/recaptcha`).
- Build and start: `docker compose up --build`. This installs XAMPP 5.6.3, starts Apache+MySQL, and serves the repo from `/opt/lampp/htdocs`.
- App is served at http://localhost:8080/main/index.php (other sections live under their folders, e.g. `/imobil`, `/chirie`, `/photos`).
- MySQL is the XAMPP bundle in the same container, exposed on port 3306 with root user and empty password by default (persisted in the `db_data` volume). The entrypoint initializes MySQL on a fresh volume and auto-creates the `DB_NAME` (default `cst`).
- If you need a clean DB reset: `docker compose down -v` then `docker compose up --build` to reinit MySQL.
- To import data from a `.sql` backup: `docker compose exec -T app mysql -uroot cst < path/to/backup.sql`.
- To import from the provided `cst030515_1225.sql.tar.gz`: extract `tar -xvf cst030515_1225.sql.tar.gz` then `docker compose exec -T app mysql -uroot cst < cst030515_1225.sql`.
- Runtime config overrides (mapped to `common/Config.php`): `APP_BASE_URL`, `COOKIE_DOMAIN`, `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`.
