#!/bin/bash

DATA=`date +"%Y-%m-%d_%H-%M"`
PATHB=/var/backups/pinguis

# Бэкапим дампом
docker exec $1 /usr/bin/mysqldump -u root --password=rootpass $2 > "$PATHB"/"$DATA"-$2.sql
# Жмем
/bin/gzip "$PATHB"/"$DATA"-$2.sql
# Чистим, удаляя файлы старше 10-ти дней
/usr/bin/find "$PATHB" -type f -mtime +10 -exec rm -rf {} \;