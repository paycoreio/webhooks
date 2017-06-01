#!/usr/bin/env bash
set -xe

composer install -o -q -n

TAGGED="paymaxi/webhooks:${TRAVIS_TAG}"
LATEST="paymaxi/webhooks:latest"

docker build -f etc/docker/php/Dockerfile.prod -t ${TAGGED} -t ${LATEST} .

docker push ${TAGGED}
docker push ${LATEST}
