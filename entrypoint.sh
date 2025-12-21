#!/bin/bash
set -e

if [ -f /var/www/configs/local-vhosts.conf ]; then
  cp /var/www/configs/local-vhosts.conf /opt/lampp/etc/extra/local-vhosts.conf
  sed -i 's/\r$//' /opt/lampp/etc/extra/local-vhosts.conf
  grep -qF "Include etc/extra/local-vhosts.conf" /opt/lampp/etc/httpd.conf || echo "Include etc/extra/local-vhosts.conf" >> /opt/lampp/etc/httpd.conf
fi

if [ -f /opt/lampp/etc/extra/httpd-xampp.conf ]; then
  sed -i 's/\r$//' /opt/lampp/etc/extra/httpd-xampp.conf
  sed -i 's/Require local/Require all granted/g' /opt/lampp/etc/extra/httpd-xampp.conf
fi

# Ensure MySQL data dir exists and has proper ownership; initialize if empty volume is mounted.
if [ ! -d /opt/lampp/var/mysql/mysql ]; then
  echo "Initializing MySQL data directory..."
  chown -R mysql:mysql /opt/lampp/var/mysql
  /opt/lampp/bin/mysql_install_db --user=mysql --ldata=/opt/lampp/var/mysql
fi
chown -R mysql:mysql /opt/lampp/var/mysql

# Ensure log exists so tail does not fail.
touch /opt/lampp/logs/error_log

/opt/lampp/lampp start

# Wait for MySQL to be ready, then ensure target DB exists
DB_NAME="${DB_NAME:-cst}"
for i in {1..20}; do
  if /opt/lampp/bin/mysqladmin ping -uroot --silent; then
    echo "Ensuring database '${DB_NAME}' exists..."
    /opt/lampp/bin/mysql -uroot -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8 COLLATE utf8_general_ci;"
    /opt/lampp/bin/mysql -uroot -e "SHOW DATABASES LIKE '${DB_NAME}';" | grep -qx "${DB_NAME}" || {
      echo "Database '${DB_NAME}' was not created / not visible to root" >&2
      exit 1
    }
    break
  fi
  sleep 1
done

# Keep container alive, stream Apache log; MySQL log may not exist by default.
exec tail -F /opt/lampp/logs/error_log
