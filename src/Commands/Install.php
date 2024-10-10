<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Builders\StaticConfigBuilder;
use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'install', description: 'Install inas', )]
class Install extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (is_dir($config = Helper::configDirectory())) {
            $output->writeln('Looks like inas is already installed.');

            $output->writeln("Run `rm -rf $config` to delete current installation.");
            $output->writeln('<error>Warning: this deletes all config and all databases.</error>');

            return 1;
        }

        // Create config directory (~/.config/inas)
        mkdir(Helper::configDirectory());

        // Create server config folders
        mkdir(Helper::serverConfigFolder());
        mkdir(Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'apache');
        mkdir(Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'nginx');

        $nginxConf = Helper::configDirectory().DIRECTORY_SEPARATOR.'nginx.conf';
        file_put_contents($nginxConf, StaticConfigBuilder::buildNginxConf());

        $proxyConf = Helper::configDirectory().DIRECTORY_SEPARATOR.'proxy.conf';
        file_put_contents($proxyConf, StaticConfigBuilder::buildProxyConf());

        // Create volume folders
        mkdir(Helper::volumesFolder());
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'apache_logs');
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'nginx_logs');
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'mysql');

        // Create default config file
        $config = new Config;
        $config->save(Helper::configFile());

        $output->writeln('<info>inas installed successfully.</info>');

        return 0;
    }
}
