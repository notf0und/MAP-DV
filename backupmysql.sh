#!/bin/sh

mysqldump=`which mysqldump`
gzip=`which gzip`
find=`which find`

# Options
backup_path="/home/sistemas/Documents/dasamericas/BackupSQL"
mysqlhost="127.0.0.1"
mysqluser="root"
mysqlpassword=""
mysqldatabases="dasamericas"
backup_name=database_$(date +%Y_%m_%d).sql

# First of all, delete backups which are older than 7 days
$find $backup_path -name '*.sql.gz' -and -mtime +7 | xargs rm > /dev/null 2>&1

# Do the backup
$mysqldump -h $mysqlhost -u $mysqluser -p --comments -R --databases $mysqldatabases > $backup_path/$backup_name
$gzip $backup_path/$backup_name
