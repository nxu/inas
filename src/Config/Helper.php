<?php

namespace Nxu\Nap56\Config;

class Helper
{
    public static function isValidProject(string $projectsFolder, string $folder): bool
    {
        return is_dir($projectsFolder.DIRECTORY_SEPARATOR.$folder);
    }

    public static function currentProject(): string
    {
        $folders = explode(DIRECTORY_SEPARATOR, getcwd());

        return end($folders);
    }

    public static function configDirectory(): string
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.config'.DIRECTORY_SEPARATOR.'nap56';
    }

    public static function configFile(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'nap56.json';
    }

    public static function sitesFolder(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'sites';
    }

    public static function volumesFolder(): string
    {
        return self::configDirectory().DIRECTORY_SEPARATOR.'volumes';
    }

    public static function siteConfig(string $site): string
    {
        return self::sitesFolder().DIRECTORY_SEPARATOR.$site.'.conf';
    }
}
