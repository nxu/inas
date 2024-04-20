#!/usr/bin/env bash

if [ ! -d /usr/local/bin ]; then
  echo "/usr/local/bin folder does not exist"
  exit
fi

curl -s -o /usr/local/bin/inas "https://raw.githubusercontent.com/nxu/inas/main/bin/inas"
chmod +x /usr/local/bin/inas

if [ ! -d ~/.config/inas ]; then
  /usr/local/bin/inas install
fi

echo "inas has been installed to /usr/local/bin/inas"
