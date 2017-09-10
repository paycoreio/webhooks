#!/usr/bin/env bash

set -eo pipefail

[[ "${DEBUG}" == true ]] && set -x

containsElement () {
  local e match="$1"
  shift
  for e; do [[ "$e" == "$match" ]] && return 0; done
  return 1
}


ENV_FILE=${ENV_FILE:-.env}
ENV_KEYS=()

if [ ! -n "${KEY_PREFIX}" ];then
  echo "KEY_PREFIX should be defined."
  exit 1
else
    KEY_PREFIX="${KEY_PREFIX}_"
    while IFS='' read -r line || [[ -n "$line" ]]; do
        STRING_PARTS=(${line//=/ })
        KEY=${STRING_PARTS[0]}
        ENV_KEYS=("${ENV_KEYS[@]}" ${KEY})
    done < "${ENV_FILE}"


    for key in $(compgen -e|grep ${KEY_PREFIX});
    do
        VALUES=`env | grep -E "^$key=(.)"`
        VALUE=(${VALUES//=/ })
        KEY=${key/${KEY_PREFIX}/}
        if [[ `containsElement "${KEY}" "${ENV_KEYS[@]}"` -eq 0 ]]; then
            sed -i "s|^${KEY}=.*|${KEY}=${VALUE[1]}|g" ${ENV_FILE}
        fi
    done
fi