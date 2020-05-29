#!/bin/bash

TEMPLATE=$1

eval "cat <<EOF
$(<$TEMPLATE)
EOF
" 2> /dev/null

