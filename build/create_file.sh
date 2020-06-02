#!/bin/bash

DIR="$0"

ENV=$1
TEMPLATE=$2

DIR="$(dirname $DIR)"
source ${DIR}/vars/${ENV}.sh

eval "cat <<EOF
$(<$TEMPLATE)
EOF
" 2> /dev/null
