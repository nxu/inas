<?php

namespace Nxu\Inas\Builders;

use Bayfront\ArrayHelpers\Arr;
use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;

class ComposeYamlGenerator
{
    public function generate(Config $config): void
    {
        $paths = [];
        $vhostFiles = [];

        foreach ($config->sites as $site) {
            // Generate vhost file
            $vhost = Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'apache'.DIRECTORY_SEPARATOR.$site->name.'.conf';
            @unlink($vhost);
            file_put_contents($vhost, VhostBuilder::build($site));
            $vhostFiles[$site->php][] = "$vhost:/etc/apache2/sites-enabled/$site->name.conf";

            // Generate nginx config file
            $conf = Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'nginx'.DIRECTORY_SEPARATOR.$site->name.'.conf';
            @unlink($conf);
            file_put_contents($conf, NginxConfBuilder::build($site));

            $paths[$site->php][] = "$site->baseDir:/var/www/html/$site->name";
        }

        $yaml = $this->buildYaml($paths, $vhostFiles);

        file_put_contents(Helper::composeFile(), $yaml);
    }

    private function buildYaml(array $paths, array $vhostFiles): string
    {
        $apacheLogDir = Helper::volumesFolder().DIRECTORY_SEPARATOR.'apache_logs';

        $nginxLogDir = Helper::volumesFolder().DIRECTORY_SEPARATOR.'nginx_logs';
        $nginxConfDir = Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'nginx';

        $mysqlDir = Helper::volumesFolder().DIRECTORY_SEPARATOR.'mysql';

        $sites56 = collect(Arr::get($paths, '5.6', []))
            ->map(fn ($line) => "      - \"$line\"")
            ->join("\n");

        $sites71 = collect(Arr::get($paths, '7.1', []))
            ->map(fn ($line) => "      - \"$line\"")
            ->join("\n");

        $vhosts56 = collect(Arr::get($vhostFiles, '5.6', []))
            ->map(fn ($line) => "      - \"$line\"")
            ->join("\n");

        $vhosts71 = collect(Arr::get($vhostFiles, '7.1', []))
            ->map(fn ($line) => "      - \"$line\"")
            ->join("\n");

        return <<<YAML
name: inas
services:
  web56:
    image: nabunub/apache-php5.6:main
    expose:
      - "80"
    networks:
      - net
    volumes:
      - "$apacheLogDir/:/var/log/apache2/"
$sites56
$vhosts56

  web71:
    image: nabunub/apache-php7.1:main
    expose:
      - "80"
    networks:
      - net
    volumes:
      - "$apacheLogDir/:/var/log/apache2/"
$sites71
$vhosts71

  nginx:
    image: nginx:1.25.4
    ports:
      - "80:80"
    networks:
      - net
    volumes:
      - "/Users/nxu/code/php56-example/config/nginx/nginx.conf:/etc/nginx/nginx.conf"
      - "/Users/nxu/code/php56-example/config/nginx/includes:/etc/nginx/includes/"
      - "$nginxConfDir/:/etc/nginx/conf.d/"
      - "$nginxLogDir/:/var/log/nginx/"

  mysql:
    platform: linux/x86_64 # mysql:5.7 image does not support arm64
    image: mysql:5.7
    command: --default-authentication-plugin=mysql_native_password
    environment:
      - MYSQL_ROOT_PASSWORD=root
    ports:
      - "3356:3306"
    networks:
      - net
    volumes:
      - "$mysqlDir/:/var/lib/mysql"

networks:
  net: {}

YAML;
    }
}
