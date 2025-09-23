#!/bin/bash

# Script to fix Laravel log file permissions
# This can be run manually or via cron

# Change to the project directory
cd "$(dirname "$0")"

# Define paths
LOG_DIR="storage/logs"
STORAGE_DIR="storage"

# Ensure the script is run as the appropriate user
# Uncomment and modify this if needed
# if [ "$(whoami)" != "www-data" ]; then
#   echo "This script must be run as www-data user"
#   exit 1
# fi

echo "Fixing permissions for Laravel log files..."

# Fix storage directory permissions
chmod -R 775 $STORAGE_DIR
echo "Set 775 permissions on $STORAGE_DIR directory"

# Fix log directory permissions
chmod 775 $LOG_DIR
echo "Set 775 permissions on $LOG_DIR directory"

# Fix permissions for all log files
find $LOG_DIR -name "*.log" -type f -exec chmod 666 {} \;
echo "Set 666 permissions on all log files"

# Create today's log file if it doesn't exist and set permissions
TODAY=$(date +"%Y-%m-%d")
TODAY_LOG="$LOG_DIR/laravel-$TODAY.log"

if [ ! -f "$TODAY_LOG" ]; then
  touch "$TODAY_LOG"
  echo "Created today's log file: $TODAY_LOG"
fi

chmod 666 "$TODAY_LOG"
echo "Set 666 permissions on today's log file: $TODAY_LOG"

echo "Log permissions fixed successfully!"
