#!/usr/bin/env bash
set -xe

if ( [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "7.0" ] ); then
  if ([ "$TRAVIS_BRANCH" == "master" ]) ; then
    composer install -o -q -n
    docker login -u ${DOCKER_USER} -p ${DOCKER_PASSWORD} -e ${DOCKER_EMAIL}

    LATEST="paymaxi/webhooks:latest"
    docker build -f etc/docker/php/Dockerfile.prod -t ${LATEST} .
    docker push ${LATEST}
  fi

  if ([ ! -z "$TRAVIS_TAG" ]); then
    TAGGED="paymaxi/webhooks:${TRAVIS_TAG}"
    echo 'SYMFONY_ENV=prod' >> .env
    echo 'SYMFONY_DEBUG=0' >> .env
    docker build -f etc/docker/php/Dockerfile.prod -t ${TAGGED} .
    docker push ${TAGGED}
  fi
fi