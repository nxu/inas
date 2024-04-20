# 🤵‍inas
A dockerized developer environment for working with legacy PHP projects. Includes Apache2, PHP 5.6, PHP 7.1 and MySQL 5.7.

## System requirements
- PHP 8.2
- Docker (or compatible software like OrbStack)

You also need to be able to resolve .test domains to 127.0.0.1. 
- If you have Laravel Valet or Herd installed, you already got this done. Run `sudo brew services start dnsmasq` after stopping
Valet or Herd.
- Or you can manually add your sites to your `/etc/hosts` file

## Installation
### Automatic install
```sh
sh <(curl https://raw.githubusercontent.com/nxu/inas/main/install.sh)
```

This downloads the inas PHAR file to /usr/local/bin/inas, gives it
executable permissions and runs `inas install`.

You'll be able to run `inas` from anywhere.

### Manual install
Download `inas` from the `bin` folder and run `inas install`.

## Usage

