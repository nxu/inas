<?php

namespace Nxu\Nap56\Commands;

use Nxu\Nap56\Config\Config;
use Nxu\Nap56\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'install', description: 'Install nap56', )]
class Install extends Command
{
    protected function configure(): void
    {
        $this->addArgument(
            name: 'folder',
            mode: InputArgument::OPTIONAL,
            description: 'Folder containing the projects you want to run with PHP 5.6. This will be mounted in the docker container',
            default: '~/code',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (is_dir($config = Helper::configDirectory())) {
            $output->writeln('<comment>Looks like nap56 is already installed.</comment>');

            // Do not run rm -rf automatically
            // @see https://github.com/valvesoftware/steam-for-linux/issues/3671
            $output->writeln("Run `rm -rf $config` to delete current installation.");

            return 1;
        }

        mkdir(Helper::configDirectory());
        mkdir(Helper::sitesFolder());
        mkdir(Helper::volumesFolder());

        $config = new Config(projectsFolder: $input->getArgument('folder'));
        $config->save(Helper::configFile());

        $output->writeln('<info>nap56 installed successfully.</info>');
        $output->writeln($config->projectsFolder.' will be mounted in the docker container');

        return 0;
    }
}
