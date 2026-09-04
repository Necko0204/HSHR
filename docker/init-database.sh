#!/bin/sh
set -eu

echo "Converting the UTF-16 database dump to UTF-8 and importing it"
iconv -f UTF-16LE -t UTF-8 /docker-entrypoint-initdb.d/database.sql.utf16 \
    | mariadb --protocol=socket --user=root --password="$MARIADB_ROOT_PASSWORD" "$MARIADB_DATABASE"
