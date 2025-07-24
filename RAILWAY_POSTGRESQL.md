# 🐘 Railway PostgreSQL + Laravel Configuration

## ✅ Current Setup:

### 1. Docker Configuration
- **Dockerfile**: PHP 8.1 + Apache + PostgreSQL extensions
- **Extensions**: `pdo_pgsql`, `pgsql` for PostgreSQL support
- **Apache**: Configured with DocumentRoot pointing to `/public`

### 2. PostgreSQL Connection
- **Railway**: Automatically provides `DATABASE_URL`
- **Format**: `postgresql://user:pass@host:port/database`
- **Laravel**: Uses `pgsql` connection driver

### 3. Environment Variables
```env
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://... (provided by Railway)
```

## 🚂 Railway Setup Steps:

### 1. Add PostgreSQL Database
1. Railway Dashboard → **"+ New"** → **"Database"** → **"Add PostgreSQL"**
2. Railway automatically sets `DATABASE_URL`
3. Connection details appear in Variables tab

### 2. Environment Variables
Railway will automatically provide:
```env
DATABASE_URL=postgresql://postgres:password@containers-us-west-xxx.railway.app:5432/railway
PGHOST=containers-us-west-xxx.railway.app
PGPORT=5432
PGDATABASE=railway
PGUSER=postgres
PGPASSWORD=generated-password
```

### 3. Laravel Configuration
Our `.env.example` is pre-configured:
```env
DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

## 🔧 How It Works:

### 1. Build Process (Dockerfile)
```dockerfile
# Install PostgreSQL extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install dependencies
RUN composer install --no-dev
RUN npm ci && npm run build
```

### 2. Runtime Process (start.sh)
```bash
# Create .env from .env.example
# Generate APP_KEY
# Wait for PostgreSQL to be ready
# Run migrations: php artisan migrate --force
# Start Apache server
```

### 3. Database Migrations
- Executed automatically on each deploy
- Uses PostgreSQL-compatible SQL
- Laravel handles DB differences automatically

## 📊 Expected Database Tables:

After successful deploy, PostgreSQL will have:
```sql
- users (with PostgreSQL BIGSERIAL)
- viagens (trips table)
- sugestoes (suggestions)
- favoritos (favorites)
- password_resets
- failed_jobs
- personal_access_tokens
```

## 🎯 Testing PostgreSQL Connection:

### From Railway Terminal:
```bash
# Connect to PostgreSQL
psql $DATABASE_URL

# List tables
\dt

# Check users table
SELECT * FROM users LIMIT 5;
```

### From Laravel:
```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();

# Run a query
DB::table('users')->count();
```

## 🚨 Troubleshooting:

### Connection Issues:
1. Verify `DATABASE_URL` is set in Railway Variables
2. Check PostgreSQL service is running
3. Ensure firewall allows connections

### Migration Issues:
```bash
# Manual migration
php artisan migrate --force

# Reset migrations
php artisan migrate:fresh --force
```

### Permission Issues:
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage
```

## 🎉 Success Indicators:

- ✅ **Build**: Docker completes without errors
- ✅ **Database**: PostgreSQL connection established
- ✅ **Migrations**: Tables created successfully  
- ✅ **App**: Laravel loads without 500 errors
- ✅ **URL**: Dashboard accessible via Railway URL

---

**PostgreSQL + Laravel + Railway = Production Ready! 🚀**
