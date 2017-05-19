#!/usr/bin/env bash

function findContainer()
{
    echo `docker-compose ps | grep ${1} | awk -F' ' '{print $1}'`
}

if [ -z "$1" ]; then
    docker exec -ti `findContainer php` sudo -u backend /bin/bash
else
    docker exec -ti `findContainer ${1}` /bin/bash
fi