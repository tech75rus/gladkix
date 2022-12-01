#!/bin/sh

mkdir src/service
touch src/service/host.js
echo "let localhost = 'http://localhost';

export let host = localhost;" > /var/www/admin/src/service/host.js

yarn install

yarn serve