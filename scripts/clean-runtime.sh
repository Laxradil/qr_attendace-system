#!/usr/bin/env bash
# Clean runtime artifacts (Unix)
cd "$(dirname "$0")/.." || exit 1
rm -rf storage/framework/views/*
php artisan view:clear
php artisan cache:clear

echo "Cleaned runtime artifacts."