<?php

namespace Nxu\Inas\Builders;

class StaticConfigBuilder
{
    public static function buildNginxConf(): string
    {
        return <<<'NGINX'
user nginx;
worker_processes auto;

error_log  /var/log/nginx/error.log notice;
pid /var/run/nginx.pid;

events {
    worker_connections  1024;
}

http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    keepalive_timeout  65;

    include /etc/nginx/conf.d/*.conf;
}

NGINX;
    }

    public static function buildProxyConf(): string
    {
        return <<<'NGINX'
proxy_set_header Host $host;
proxy_set_header X-Real-IP $remote_addr;
proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
proxy_set_header X-Forwarded-Proto $scheme;
proxy_buffering off;
proxy_request_buffering off;
proxy_http_version 1.1;
proxy_intercept_errors on;

NGINX;
    }
}
