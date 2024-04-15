<?php

namespace Nxu\Inas\Commands;

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
            $output->writeln('<comment>Looks like nap56 is already installed.</comment>');

            // Do not run rm -rf automatically
            // @see https://github.com/valvesoftware/steam-for-linux/issues/3671
            $output->writeln("Run `rm -rf $config` to delete current installation.");

            return 1;
        }

        // Create config directory (~/.config/inas)
        mkdir(Helper::configDirectory());

        // Create server config folders
        mkdir(Helper::serverConfigFolder());
        mkdir(Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'apache');
        mkdir(Helper::serverConfigFolder().DIRECTORY_SEPARATOR.'nginx');

        // Create volume folders
        mkdir(Helper::volumesFolder());
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'apache_logs');
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'nginx_logs');
        mkdir(Helper::volumesFolder().DIRECTORY_SEPARATOR.'mysql');

        // Create default config file
        $config = new Config();
        $config->save(Helper::configFile());

        $output->writeln('<info>inas installed successfully.</info>');

        return 0;
    }
}
