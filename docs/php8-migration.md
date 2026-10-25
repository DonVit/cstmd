# PHP 8 Migration Plan (PHP2Shell Response)

Living document for migrating cstmd from PHP 5.6 to PHP 8.4 in response to the
critical "PHP2Shell" vulnerabilities. Track status and log completed actions at
the bottom of this file.

- Created: 2026-10-22
- Owner: (assign)
- Target completion: (set deadline)

## 1. Background — why this migration is urgent

PHP released security updates for two critical unauthenticated RCE
vulnerabilities ("PHP2Shell"), both CVSS v4.0 9.2, with attack traffic reported
by Cloudflare:

- **CVE-2026-109618** — Out-of-memory corruption: an OOM error interrupting a
  vulnerable operation leaves request data partially initialized; cleanup
  routines process the invalid state, creating a path to RCE.
- **CVE-2026-109619** — Stale global state: PHP retains references to freed
  request data; a later request on the same worker hits stale references
  (use-after-free). RCE demonstrated.

Fixed upstream versions:

| Branch | Fixed in |
|---|---|
| PHP 8.5 | 8.5.12 |
| PHP 8.4 | 8.4.27 |
| PHP 8.3 | 8.3.36 |
| PHP 8.2 | 8.2.35 |

PHP 8.1 and earlier are out of regular support (limited source backports for
7.4/8.0/8.1 only). **PHP 5.6 receives no fix.**

References:

- Qualys write-up: https://blog.qualys.com/vulnerabilities-threat-research/2026/10/20/php-announces-critical-security-release-for-two-vulnerabilities
- PHP announcement: https://www.php.net/archive/2026.php#2026-10-20-1

## 2. Current exposure

- Local dev: `Dockerfile` installs **XAMPP 5.6.3 (PHP 5.6.3)** on Ubuntu 22.04.
- Production: rsync-deployed via GitHub Actions to `/var/www/cstmdlive/` on the
  live server — internet-facing, highest priority.

**Live server confirmed (2026-10-23, via https://casata.md/phpinfo.php):**

| Item | Live value | Implication |
|---|---|---|
| PHP version | 5.6.3 (XAMPP, built 2014-11-17) | Unpatched; no upstream fix for 5.6 |
| Server API | Apache 2.0 Handler (**mod_php**) | Highest-risk SAPI per the advisory — demonstrated exploit fires before app code runs |
| Apache | 2.4.10, prefork | |
| `session.upload_progress.enabled` | **On** | Confirmed CVE-2026-109619 attack path is open |
| `post_max_size` / `upload_max_filesize` | **512M / 512M** | Large OOM-pressure surface (CVE-2026-109618) |
| `php.ini` | `/opt/lampp/etc/php.ini` | Same XAMPP layout as the Docker image |
| Doc root | `/var/www/cstmdlive/cstmd/main` | Matches the rsync deploy target |
| mysqli extension | **enabled** (mysqlnd) | A mysqli-converted codebase runs on the current prod runtime without changes — migration can go code-first |
| Other | `display_errors=On`, public `main/phpinfo.php`, mcrypt/ereg/mssql extensions loaded | Info disclosure; additional migration blockers to audit (mcrypt, ereg) |

## 3. Compatibility blockers found (audit of 2026-10-22)

Audit of ~244 PHP files found these blockers:

| Blocker | Location | Removed in |
|---|---|---|
| `mysql_*` API — 83 call sites in 17 files, centralized in the DB layer | `common/DBConnection.php`, `common/DBManager.php`, `common/DBTreeManager.php`, `common/CoCaemL.php`, `common/CoCaemNL.php`, `common/CoCompany.php`, `common/Table.php`, `common/MainWebPage.php`, `calendar/index.php`, `primarii/index.php`, `photos/resizeimgs.php`, `news/newskml.php`, `tools/osmmigrator.php`, `labs/gmaps/*` | PHP 7.0 |
| PHP4-style constructors (`function ClassName(){`) | `common/DBManager.php`, `common/DBTreeManager.php`, likely more classes in `common/` | PHP 8.0 |
| `__autoload()` | `main/loader.php` | PHP 8.0 |
| `split()` | `labs/gbase/demo.php` | PHP 7.0 |
| `count(null)` fatals — `DBManager::doSql()` returns `null`, callers pass it to `count()` | `common/DBManager.php` + callers | PHP 8.0 (TypeError) |
| Dynamic properties on `Object`-derived classes (`$o->$n = $row->$n`) | `common/DBManager.php`, `common/Object.php` | Deprecated PHP 8.2 |
| mcrypt usage (TBD — extension loaded on live, no code hits found in initial audit; re-verify) | (audit pending) | Removed PHP 7.2 |
| ereg usage (TBD — extension loaded on live, no code hits found in initial audit; re-verify) | (audit pending) | Removed PHP 7.0 |

Confirmed NOT present (no action needed):

- No `$HTTP_*_VARS`, `each()`, `create_function()`, `preg_replace /e`,
  `mcrypt_*`, magic quotes usage.
- `common/lib/recaptcha` submodule is the modern namespaced library — compatible.

## 4. Migration plan

### Phase 0 — Immediate risk reduction — ✅ DONE on live (2026-10-25)

- [x] On the **live server**, set `session.upload_progress.enabled=0` in php.ini
      and restart Apache (blocks the confirmed session-lifecycle attack path;
      valid on 5.6 too). Verified live: `upload_progress=0`.
- [x] Tighten `post_max_size` / `upload_max_filesize`; enforce request-size
      limits at Apache. Verified live: `post_max_size=8M`, `upload_max_filesize=4M`
      (was 512M/512M).
- [x] Restrict public access to `tools/`, `labs/`, `stats/` until patched.
      Verified live: tools.casata.md redirects to error.php externally.
- [x] Confirm what PHP the live server actually runs — confirmed **PHP 5.6.3,
      XAMPP, mod_php** via https://casata.md/phpinfo.php on 2026-10-23.
- [x] Remove or access-restrict the public `main/phpinfo.php` on the live
      server — renamed to `phpinfo.php.disabled` on 2026-10-25.

#### Phase 0 runbook — applying the mitigation

**Local (Docker):** the mitigation is included automatically — `Dockerfile`
and `entrypoint.sh` copy `configs/php2shell-mitigation.conf` into
`/opt/lampp/etc/extra/` and add an `Include` to `httpd.conf`. Rebuild:

```bash
docker compose up --build -d
```

**Live server (manual, confirmed XAMPP 5.6.3 + mod_php at /opt/lampp):**

1. Copy `configs/php2shell-mitigation.conf` to
   `/opt/lampp/etc/extra/php2shell-mitigation.conf` on the server.
2. Add `Include etc/extra/php2shell-mitigation.conf` to
   `/opt/lampp/etc/httpd.conf` (mod_php is in use, so the `php_value`
   directives in the conf WILL apply).
3. Validate config: `/opt/lampp/bin/apachectl configtest`, then restart:
   `/opt/lampp/lampp restart`.
4. Verify: `https://casata.md/phpinfo.php` shows
   `session.upload_progress.enabled = Off` and
   `post_max_size = 8M`; `https://tools.casata.md/` returns 403 from a
   non-localhost IP.
5. Also delete or restrict `main/phpinfo.php` (it leaks full config).

> Note: `memory_limit=-1` was deliberately NOT set (it trades the OOM attack
> path for resource-exhaustion risk).

### Phase 1 — Target & tooling

- [ ] Target **PHP 8.4** (fixed at 8.4.27; security support to end of 2028).
      Skip 7.x entirely.
- [ ] Create branch `php8-migration`.
- [ ] Run `php -l` sweep across all PHP files (syntax sanity).
- [ ] Run **PHPCompatibility** (phpcs standard) for a full compat report.
- [ ] Optionally run **Rector** to auto-fix constructors and `__autoload`.

### Phase 2 — Code remediation

- [x] **DB layer → mysqli**, shim-first:
  1. ✅ Drop-in compat shim created: `common/lib/mysql-shim.php`
  2. ✅ Rewrote `common/DBConnection.php`, `common/DBManager.php`,
     `common/DBTreeManager.php` natively with mysqli
  3. ⏳ Convert remaining page files with direct `mysql_*` calls (shim covers them for now)
- [x] Convert all PHP4 constructors to `__construct()` — 2 found & fixed (DBManager, DBTreeManager)
- [x] Replace `__autoload` with `spl_autoload_register` in `main/loader.php`
- [ ] Fix `split()` in `labs/gbase/demo.php` (low priority, labs section)
- [ ] Sweep `count($maybeNull)` call sites → null-safe checks
- [ ] Add `#[AllowDynamicProperties]` to `common/Object.php` as a stopgap
- [x] Convert `common/Table.php` to native mysqli (was heavy mysql_* user)

### Phase 3 — Runtime rebuild

- [ ] Replace XAMPP Dockerfile with official `php:8.4-apache` +
      `docker-php-ext-install mysqli gd`; split MySQL into a separate
      `mysql:8` compose service.
- [ ] Rework `entrypoint.sh` (no more `/opt/lampp`); keep the vhosts include.
- [ ] Verify uploads, image resize, sessions, and `mail()` (needs MTA/SMTP in
      the new container).

### Phase 4 — Validation

- [ ] `error_reporting(E_ALL)` in dev; smoke-test every vhost section: main,
      news, photos upload, imobil/chirie add forms, accounts login/register
      (recaptcha), ads, sitemaps/rss/xml endpoints.
- [ ] DB smoke: import `cst030515_1225.sql` backup into MySQL 8; check charset
      (consider `utf8` → `utf8mb4` conversion here).

### Phase 5 — Production rollout

- [ ] Decide prod strategy: install PHP 8.4 packages on the live server
      (fastest) or containerize prod like local.
- [ ] Full backup first; keep the old stack available for rollback for a week.
- [ ] Deploy via existing GitHub Actions rsync flow.
- [ ] Verify the **running** runtime is 8.4.27+ (not just installed).

### Phase 6 — Cleanup

- [ ] Remove Phase 0 mitigations where no longer needed; set sane
      `memory_limit`.
- [ ] Update `README.md` (setup/commands) and `AGENTS.md`.

## 5. Action log

Append dated entries here as work is done. Keep newest first.

| Date | Action | Result / notes |
|---|---|---|
| 2026-10-22 | Codebase audit for PHP 8 blockers (grep sweeps for removed APIs, constructors, autoload) | Findings recorded in section 3 |
| 2026-10-22 | Migration plan drafted | This document |
| 2026-10-23 | Phase 0: created `configs/php2shell-mitigation.conf` (session upload-progress off, request-size limits, localhost-only tools/labs/stats); wired into `Dockerfile` + `entrypoint.sh` | Done locally; container rebuild pending (Docker Desktop was not running). Live-server application still open — see runbook above |
| 2026-10-23 | Confirmed live runtime via https://casata.md/phpinfo.php: PHP 5.6.3 XAMPP, **mod_php**, upload_progress **On**, post_max_size 512M, mysqli available | Server fully exposed; mitigation on prod is urgent. Runbook updated for confirmed mod_php setup. New follow-ups: restrict/delete public phpinfo.php; audit mcrypt/ereg code usage |
| 2026-10-25 | Phase 0 applied to live: mitigation conf deployed (DirectoryMatch fixed to cover prod docroot `/var/www/cstmdlive/cstmd/`), expired/missing Let's Encrypt cert re-issued (was blocking Apache restart), `lampp restart` done, phpinfo.php renamed to `.disabled` | **Verified live:** upload_progress=0, post_max_size=8M, upload_max_filesize=4M, tools/labs/stats blocked externally, main site functional. Note: `apachectl configtest` needs sudo (cert files are root-owned) — non-sudo run reports a misleading SSL cert error |
| 2026-10-25 | Phase 2 started: created `feat/php8-migration` branch, rewrote DB layer (DBConnection/DBManager/DBTreeManager) to native mysqli, created mysql_* shim for remaining files, fixed PHP4 constructors, replaced `__autoload` with `spl_autoload_register`, converted Table.php | App should boot on PHP 8 with shim; remaining page files use shim until converted |
