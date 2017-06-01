#!/usr/bin/env bash
set -xe

composer install -o -q -n

docker build -f etc/docker/php/Dockerfile.prod -t dzubchik/webhooks .