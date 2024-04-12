<?php

namespace Nxu\Inas\Builders;

use Nxu\Inas\Config\InstalledSite;

class NginxConfBuilder
{
    public static function build(InstalledSite $site): string
    {
        $server = match ($site->php) {
            '5.6' => 'web56',
            '7.1' => 'web71',
        };

        return <<<ENDCONF
server {
    listen 80;
    server_name {$site->name}.test;

    location / {
        include /etc/nginx/includes/proxy.conf;
        proxy_pass http://$server:80;
    }

    access_log off;
    error_log /var/log/nginx/error.log error;
}

ENDCONF;
    }
}
