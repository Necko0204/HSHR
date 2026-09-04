# HSHR

Human resource management system for Holy Spirit School of Imus, Inc. The
application includes an administrator workspace and an employee self-service
portal for attendance, payroll, leave, reports, profiles, and messaging.

## Run locally with Docker

Docker Desktop is the only prerequisite. From the repository root, run:

```powershell
docker compose up --build -d
```

Then open <http://localhost:8080>.

The first run creates a MariaDB database, imports a local `database.sql`, and
applies the idempotent compatibility migration in `docker/20-local-schema.sql`.
The dump is intentionally ignored because database exports may contain employee
PII; obtain a scrubbed development dump through your approved internal channel.

- Administrator login: <http://localhost:8080>
- Employee login: <http://localhost:8080/staff_side/>

To stop the application, run `docker compose down`. To recreate the local
database from scratch, run `docker compose down --volumes` and then start the
stack again.

## Front-end architecture

- `assets/css/hshr-next.css` and `assets/js/hshr-next.js` provide the shared
  administrator shell, navigation, appearance controls, and message center.
- `assets/css/staff-next.css` and `assets/js/staff-next.js` provide the shared
  employee portal shell and responsive service layout.
- Dashboard-specific scripts are deliberately small and do not load animation
  or chart frameworks.

Both shells use the existing school crimson identity, system fonts, responsive
off-canvas/mobile navigation, consistent cards and forms, and reduced-motion
support.

## Back-end notes

- Database access is configured from environment variables through
  `database_connection.php`.
- Administrative mutation endpoints require an authenticated admin session via
  `includes/admin_api.php`.
- Employee mutation endpoints use the equivalent staff API guard. Both portals
  enforce CSRF tokens, secure session cookies, idle expiry, and active-account checks.
- Messaging identifies the sender from the authenticated session and uses
  prepared statements.
- New and changed passwords use PHP's adaptive `password_hash` format. Existing
  SHA-256 records are accepted once and upgraded after a successful sign-in.
- SMTP and OpenAI features are disabled until their environment variables are
  configured; secrets must never be committed to the repository.
- Local schema compatibility additions are safe to reapply.

Copy `.env.example` to a local `.env`, replace every placeholder, and keep the
result outside version control. Never use a production database export as local
seed data unless it has been formally anonymized.

## Verification

After rebuilding, lint first-party PHP files inside the container with:

```powershell
docker compose exec -T app sh -lc "find /var/www/html -path /var/www/html/vendor -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l"
```

Then validate dependencies and inspect service health:

```powershell
docker compose exec -T app composer validate --strict
docker compose exec -T app composer audit --no-dev
docker compose ps
```
