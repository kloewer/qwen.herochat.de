# Kanban Board - Setup Instructions

## Database Setup (kasserver.com)

1. **Create a MySQL database** in your kasserver control panel (Kas)

2. **Note your credentials:**
   - Database name (e.g., `db123456789`)
   - Database user (e.g., `db123456789`)
   - Database password

3. **Import the schema:**
   - Open phpMyAdmin from your kasserver panel
   - Select your database
   - Go to SQL tab
   - Run the contents of `schema.sql`

4. **Configure database connection:**
   - Edit `config.php` on the server (or locally before deploy)
   - Update the credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'db123456789');  // Your database name
     define('DB_USER', 'db123456789');  // Your database user
     define('DB_PASS', 'your_password'); // Your database password
     ```

5. **Deploy** using the deploy script:
   ```bash
   bash .deploy.sh
   ```

## Features

- ✅ Add new cards to any column
- ✅ Drag and drop cards between columns
- ✅ Add/edit/delete checklist items
- ✅ Check/uncheck tasks inline on cards
- ✅ Check/uncheck tasks in modal
- ✅ Progress bar shows completion status
- ✅ All changes sync to MySQL database

## File Structure

```
├── index.html      # Frontend (TailwindCSS + Vanilla JS)
├── api.php         # PHP REST API
├── config.php      # Database credentials (exclude from git)
├── schema.sql      # Database schema (exclude from git)
├── .deploy.sh      # FTP deploy script
└── .env.example    # Example config template
```
