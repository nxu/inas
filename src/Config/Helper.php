<?php

namespace Nxu\Inas\Config;

class Helper
{
    public static function ensureInstalled(): ?string
    {
        if (! is_dir(self::serverConfigFolder())) {
            return '<error>The config folder for inas does not exist. Please run `inas install`</error>';
        }

        return null;
    }

    public static function configDirectory(): string
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.config'.DIRECTORY_SEPARATOR.'inas';
    }

    public static function configFile(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'inas.json';
    }

    public static function serverConfigFolder(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'serverconfig';
    }

    public static function volumesFolder(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'volumes';
    }
}
