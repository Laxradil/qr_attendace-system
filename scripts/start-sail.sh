#!/usr/bin/env bash
# Start Laravel Sail (Unix)
cd "$(dirname "$0")/.." || exit 1
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --force

echo "Sail started and migrations ran. Use './vendor/bin/sail down' to stop."