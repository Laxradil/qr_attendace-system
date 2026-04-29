# Team Setup Guide - QR Attendance System

Follow these steps to get the project running with the shared Supabase database.

## Step 1: Pull the Branch

```bash
git pull origin branch_pelep
```

## Step 2: Create Your `.env` File

The `.env` file contains your local configuration and **should never be committed to Git**.

**Copy the template:**
```bash
cp .env.example .env
```

This creates a new `.env` file in your project root with the template.

## Step 3: Fill in Supabase Connection Details

Open your `.env` file and find the database section. Fill in these values:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-northeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.xcofziseyxlkzygqnahv
DB_PASSWORD=z3ajAmX9aAkkXKdY
DB_SSLMODE=require
```

**Do NOT share or commit this file** — it contains the database password.

## Step 4: Verify PostgreSQL Driver

Check if your PHP has the PostgreSQL driver enabled:

```bash
php --ri pdo_pgsql
```

**If you see `Extension 'pdo_pgsql' not present`:**

1. Find your `php.ini` file
2. Uncomment this line: `extension=pdo_pgsql`
3. Restart your PHP server

## Step 5: Run Migrations (First Time Only)

After `.env` is configured, run migrations to set up the database schema:

```bash
php artisan config:clear
php artisan migrate --force
```

You should see output like:
```
INFO  Creating migration table...
INFO  Running migrations.
  0001_01_01_000000_create_users_table .......... DONE
  0001_01_01_000001_create_cache_table ......... DONE
  ... (more migrations)
```

**Important:** Only run this once. All tables are now created in the shared Supabase database.

## Step 6: Start Developing

You're ready to use the app! All data you add will be saved to Supabase and visible to your team.

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## When New Migrations Are Added

If the team lead pushes new migrations to `branch_pelep`:

1. Pull the latest code: `git pull origin branch_pelep`
2. Run migrations again: `php artisan migrate --force`
3. New tables/columns will be added to Supabase

## Troubleshooting

### "Network is unreachable" or "Unknown host"

- Verify you're using the **pooler host**: `aws-1-ap-northeast-1.pooler.supabase.com`
- Not the direct host: `db.xcofziseyxlkzygqnahv.supabase.co`
- Check your `.env` file for typos

### "pdo_pgsql not present"

- Enable the PostgreSQL extension in your `php.ini` (see Step 4)
- Restart your PHP/Apache server

### Migration fails with "already exists"

- This shouldn't happen if you're using the shared Supabase database
- If it does, contact the team lead

## Architecture

- **Database:** Supabase PostgreSQL (shared by entire team)
- **Authentication:** Laravel built-in (local to each developer)
- **Sessions/Cache:** Database-backed (shared via Supabase)
- **Files:** Local storage on your machine

All team members share the same database, so any data you create is visible to everyone.

## Never Do This

❌ Don't commit `.env` to Git  
❌ Don't manually edit tables in Supabase dashboard (use migrations instead)  
❌ Don't share the database password  
❌ Don't use the direct Supabase host (use the pooler host)

## Questions?

Ask your team lead or check the main [README.md](README.md).
