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

    public static function composeFile(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'compose.yaml';
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

    public static function getSiteName(?string $dir = null): string
    {
        $dir = $dir ?: getcwd();

        $parts = explode(DIRECTORY_SEPARATOR, $dir);

        return end($parts);
    }
}
