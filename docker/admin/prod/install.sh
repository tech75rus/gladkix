#!/bin/sh

mkdir src/service
touch src/service/host.js
echo "let production = 'https://api.gladkix.com';

export let host = production;" > /var/www/admin/src/service/host.js

yarn install

yarn build