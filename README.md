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
Download the latest `inas` binary  from the 
[releases](https://github.com/nxu/inas/releases) and run `inas install`.

## Usage
### Add a site to Inas
Go to a site containing your PHP project and run 

```shell
cd ~/code/myproject

inas add <phpver>

# inas add 5.6
# inas add 7.1
```

This will add the site to Inas. The site will be available at `http://myproject.test` where
`myproject` is the name of the folder you added. 

### Start inas
```shell
inas start
```

### Stop inas
```shell
inas stop
```

### Remove site from inas
```shell
cd ~/code/myproject

inas remove
```

### Accessing MySQL
Your sites will be able to access the MySQL5.7 server at the host `mysql`:

```dotenv
DB_HOST=mysql
DB_PORT=3306
```

- You can access this server from your host at `127.0.0.1:3356`.
- The database will be persisted in `~/.config/inas/volumes/mysql`.

### Logs
You can find the server logs in:
-  `~/.config/inas/volumes/apache_logs` for apache logs
-  `~/.config/inas/volumes/nginx_logs` for nginx logs
