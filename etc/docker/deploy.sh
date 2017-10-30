#!/usr/bin/env bash
set -xe

if [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "7.1" ]; then
  composer install -o -q -n --no-dev
  docker login -u ${DOCKER_USER} -p ${DOCKER_PASSWORD} -e ${DOCKER_EMAIL}

  if [ "$TRAVIS_BRANCH" == "master" ] ; then
    LATEST="paymaxi/webhooks:latest"
    docker build -f etc/docker/php/Dockerfile.prod -t ${LATEST} .
    docker push ${LATEST}
  fi

  if [ ! -z "$TRAVIS_TAG" ]; then
    TAGGED="paymaxi/webhooks:${TRAVIS_TAG}"
    cp .env.dist .env
    docker build -f etc/docker/php/Dockerfile.prod -t "${TAGGED}" .
    docker push "${TAGGED}"
  fi
fi