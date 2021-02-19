#!/bin/bash
base="admin_FULLDATA"
NOW=$(date +%H_%M_%d_%m_%Y)
/usr/bin/mysqldump --defaults-file=/home/httpd/vhosts/api.kt-segment.ru/httpdocs/backups/mybackup.cnf $base | gzip > /home/httpd/vhosts/api.kt-segment.ru/httpdocs/backups/mysql_dump_every_$NOW.gzip
/home/httpd/fcgi-bin/a353561_api/php-cli /home/httpd/vhosts/api.kt-segment.ru/httpdocs/backups/backup_disk.php mysql_dump_every_$NOW.gzip
