#!/bin/bash
# TAVP Web ID — Full Setup Script
# Runs automatically on container start via events.post-start
# Can also run manually: tavpbox setup

set -e

echo ""
echo "━━━ TAVP Web ID Setup ━━━"
echo ""

# Wait for MariaDB to be ready
echo "[0/6] Waiting for MariaDB..."
for i in $(seq 1 30); do
    if mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo "  ✓ MariaDB ready"
        break
    fi
    sleep 1
done

# 1. Create DB user
echo "[1/6] Creating database user..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS tavp; CREATE USER IF NOT EXISTS 'tavp'@'localhost' IDENTIFIED BY 'tavp'; CREATE USER IF NOT EXISTS 'tavp'@'%' IDENTIFIED BY 'tavp'; GRANT ALL ON tavp.* TO 'tavp'@'localhost'; GRANT ALL ON tavp.* TO 'tavp'@'%'; FLUSH PRIVILEGES;" 2>/dev/null && echo "  ✓ Database ready"

# 2. Fix nginx root
echo "[2/6] Configuring nginx..."
if grep -q "root /var/www/html;" /etc/nginx/sites-enabled/default 2>/dev/null; then
    sed -i 's|root /var/www/html;|root /var/www/html/public;|' /etc/nginx/sites-enabled/default
    nginx -s reload 2>/dev/null || true
    echo "  ✓ Nginx root fixed"
else
    echo "  · Nginx root already correct"
fi

# 3. Create symlinks
echo "[3/6] Creating symlinks..."
for pair in "vendor:/var/www/html/vendor" "bootstrap:/var/www/html/bootstrap" "config:/var/www/html/config" "routes:/var/www/html/routes" "themes:/var/www/html/themes" "app:/var/www/html/app" "storage:/var/www/html/storage"; do
    link="/var/${pair%%:*}"
    target="${pair#*:}"
    if [ ! -e "$link" ]; then
        ln -sf "$target" "$link" 2>/dev/null && echo "  ✓ $link" || true
    fi
done

# 4. Setup database tables + settings + users
echo "[4/6] Setting up database..."
php /var/www/html/bin/setup-db.php 2>/dev/null | grep -E "^  [✓·]" || true

# 5. Seed content
echo "[5/6] Seeding content..."
if [ -f /var/www/html/bin/seed-content.php ]; then
    php /var/www/html/bin/seed-content.php 2>/dev/null | grep -E "✔|✓|UPDATED|INSERTED" || true
fi

# 6. Fix permissions
echo "[6/6] Fixing permissions..."
mkdir -p /tmp/storage/compiled/volt /tmp/storage/cms/cache /tmp/storage/cms/revisions 2>/dev/null
chmod -R 777 /tmp/storage 2>/dev/null
mkdir -p /var/www/html/public/uploads 2>/dev/null
chmod 777 /var/www/html/public/uploads 2>/dev/null
echo "  ✓ Permissions fixed"

echo ""
echo "━━━ Setup complete! ━━━"
echo "  Website:  http://tavp-web-id.tavp.my.id"
echo "  Admin:    http://tavp-web-id.tavp.my.id/admin"
echo "  Mailpit:  http://mailpit.tavp-web-id.tavp.my.id"
echo ""
