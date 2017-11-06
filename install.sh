#!/usr/bin/env bash

command -v composer >/dev/null 2>&1 || { echo "I require composer but it's not installed.  Aborting." >&2; exit 1; }
composer install
if [ "$EUID" -ne 0 ]
  then chmod +x do
fi