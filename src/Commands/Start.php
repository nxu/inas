<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Builders\ComposeYamlGenerator;
use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'start',
    description: 'Start the inas Docker developer environment',
)]
class Start extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        $configFile = Helper::configFile();

        $output->writeln("Reading config from $configFile", OutputInterface::VERBOSITY_VERBOSE);
        $config = Config::read($configFile);

        $output->writeln('Generating compose.yaml', OutputInterface::VERBOSITY_VERBOSE);
        (new ComposeYamlGenerator)->generate($config);

        $output->writeln('Running docker compose up', OutputInterface::VERBOSITY_VERBOSE);
        $output->writeln('Starting inas Docker environment');
        $process = new Process(['docker', 'compose', '-f', Helper::composeFile(), 'up', '-d']);
        $process->mustRun();

        $output->writeln('Inas has been started');

        return 0;
    }
}
