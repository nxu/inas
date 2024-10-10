<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Builders\ComposeYamlGenerator;
use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'generate',
    description: 'Generates the compose.yaml file'
)]
class Generate extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        $config = Config::read(Helper::configFile());
        (new ComposeYamlGenerator)->generate($config);

        $output->writeln('<info>Compose.yaml has been successfully generated. Run `inas start` to start</info>');

        return 0;
    }
}
