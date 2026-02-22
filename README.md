# cstmd
Automatically exported from code.google.com/p/cstmd

## Running with Docker (XAMPP 5.6.3 inside the container)
- Prereqs: Docker Desktop (Windows 10/11 — WSL2 backend) + Docker Compose (v2). Quick Windows install & verify:
  - Install Docker Desktop: https://docs.docker.com/desktop/windows/ or run `winget install --id Docker.DockerDesktop -s winget`.
  - Ensure WSL2 is enabled and Docker Desktop's WSL integration is ON; restart if prompted.
  - Verify: `docker --version`, `docker compose version`, `docker run --rm hello-world`.
  - Troubleshooting: enable virtualization in BIOS, run `wsl --update`, and make sure Docker Desktop is running.
- Pull the ReCaptcha submodule once: `git submodule update --init --recursive` (needed for `common/lib/recaptcha`).
- Build and start: `docker compose up --build`. This installs XAMPP 5.6.3, starts Apache+MySQL, and serves the repo from `/opt/lampp/htdocs`.
- App is served at http://localhost:8080/main/index.php (other sections live under their folders, e.g. `/imobil`, `/chirie`, `/photos`).
- MySQL is the XAMPP bundle in the same container, exposed on port 3306 with root user and empty password by default (persisted in the `db_data` volume). The entrypoint initializes MySQL on a fresh volume and auto-creates the `DB_NAME` (default `cst`).
- If you need a clean DB reset: `docker compose down -v` then `docker compose up --build` to reinit MySQL.
- To import data from a `.sql` backup: `docker compose exec -T app mysql -uroot cst < path/to/backup.sql`.
- To import from the provided `cst030515_1225.sql.tar.gz`: extract `tar -xvf cst030515_1225.sql.tar.gz` then `docker compose exec -T app mysql -uroot cst < cst030515_1225.sql`.
- Runtime config overrides (mapped to `common/Config.php`): `APP_BASE_URL`, `COOKIE_DOMAIN`, `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`.

## Local Development with Virtual Hosts

The application is configured with multiple virtual hosts for different sections. To access them locally using their domain names:

1. Add the following entries to your hosts file (`C:\Windows\System32\drivers\etc\hosts` on Windows, or `/etc/hosts` on Linux/macOS):

```
127.0.0.1 casata.it
127.0.0.1 common.casata.it
127.0.0.1 news.casata.it
127.0.0.1 feeds.casata.it
127.0.0.1 video.casata.it
127.0.0.1 imobil.casata.it
127.0.0.1 chirie.casata.it
127.0.0.1 companies.casata.it
127.0.0.1 maps.casata.it
127.0.0.1 images.casata.it
127.0.0.1 photos.casata.it
127.0.0.1 localitati.casata.it
127.0.0.1 www.localitati.casata.it
127.0.0.1 distante.casata.it
127.0.0.1 accounts.casata.it
127.0.0.1 stats.casata.it
127.0.0.1 anunt.casata.it
127.0.0.1 ads.casata.it
127.0.0.1 tools.casata.it
127.0.0.1 labs.casata.it
127.0.0.1 telefoane.casata.it
127.0.0.1 primarii.casata.it
127.0.0.1 dictionar.casata.it
127.0.0.1 nume.casata.it
127.0.0.1 calendar.casata.it
127.0.0.1 fm.casata.it
127.0.0.1 alegeri.casata.it
```

2. Alternatively, run the provided PowerShell script to automatically update the hosts file with all domains from `configs/local-vhosts.conf`:
   - Open PowerShell as Administrator.
   - Navigate to the project directory: `cd c:\projects\cstmd`
   - Run: `.\tools\update-hosts.ps1`
   - This script parses the Apache config and adds any missing entries to the hosts file.

3. Access the application at the respective URLs, e.g., `http://casata.it:8080` for the main site, `http://news.casata.it:8080` for news, etc.

Note: The Docker container exposes Apache on port 8080, so append `:8080` to the domain names.
