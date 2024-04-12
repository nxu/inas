<?php

namespace Nxu\Inas\Builders;

use Nxu\Inas\Config\InstalledSite;

class VhostBuilder
{
    public static function build(InstalledSite $site): string
    {
        $docRoot = empty($site->docroot) ? '' : ('/'.ltrim($site->docroot, '/'));

        return <<<ENDCONF
<VirtualHost *:80>
	ServerName {$site->name}.test
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/html/{$site->name}$docRoot
	ErrorLog \${APACHE_LOG_DIR}/{$site->name}.log
</VirtualHost>

ENDCONF;
    }
}
