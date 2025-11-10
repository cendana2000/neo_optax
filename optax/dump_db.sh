#!/bin/bash

# Get the list of databases using psql command
# DATABASES=$(psql -U postgres -h database-postgresql -l -t | cut -d \| -f 1 | grep -v -E 'template[01]' | grep -v '^ ' | tr -d '[:space:]')

# List of databases to backup
DATABASES=("pos_sena" "pos_reference")

# Backup directory
BACKUP_DIR="/var/www/html/prod/monitoringpajak/db"

# Loop through the databases and perform backups
for db in $DATABASES; do
    pg_dump -U postgres -h database-postgresql "$db" > "$BACKUP_DIR/$db-$(date +%Y%m%d%H%M%S).sql"
done