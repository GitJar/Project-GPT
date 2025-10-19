Resep Nusantara — Admin Panel ZIP
================================

Contents:
- /server : PHP backend API files (config, functions, api endpoints)
- /client/admin : React admin app (starter)

Quick start:
1. Copy /server to your PHP hosting. Ensure PHP 8+ and mysqli extension enabled.
2. Create database `resep_nusantara` and import SQL (see sql/init.sql).
3. Update server/config.php with DB credentials.
4. Make sure /server/uploads is writable by web server.
5. For React admin, run `npm install` and `npm run build`, then serve the built files.

Security notes:
- Replace default admin password immediately.
- Use HTTPS in production.
