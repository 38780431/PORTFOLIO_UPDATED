# japheth-ralph-portfolio-website-with-login-page

This project has been migrated to use Node.js with SQLite instead of a local MySQL server.

## Run locally

1. Install Node.js, then run `npm install` in the project folder.
2. Set a private session secret of at least 32 bytes. For PowerShell:

   ```powershell
   $env:SESSION_SECRET = node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
   ```

3. Configure SMTP to enable password reset emails:

   ```powershell
   $env:SMTP_HOST = "smtp.example.com"
   $env:SMTP_PORT = "587"
   $env:SMTP_SECURE = "false"
   $env:SMTP_USER = "your-smtp-username"
   $env:SMTP_PASSWORD = "your-smtp-password"
   $env:MAIL_FROM = "Portfolio Support <no-reply@example.com>"
   $env:APP_BASE_URL = "http://localhost:3000"
   ```

   Use `SMTP_SECURE = "true"` when the SMTP provider requires implicit TLS (commonly port 465). Keep these values in the deployment environment; never commit them.

4. Run `npm start`, then open `http://localhost:3000`.

For production, set `NODE_ENV=production` and serve the app over HTTPS. The session cookie will use the `Secure` flag; the app trusts one reverse-proxy hop.

The SQLite database is created at `data/app.db` and is intentionally excluded from Git. Password reset links are single-use and expire after 30 minutes. The app returns a configuration error for reset requests if SMTP is not configured.

Authenticated pages and password-reset links are excluded from the service worker cache.
