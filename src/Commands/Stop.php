<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'stop',
    description: 'Stops the inas Docker developer environment',
)]
class Stop extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        $output->writeln('Stopping inas Docker environment');
        $process = new Process(['docker', 'compose', '-f', Helper::composeFile(), 'stop']);
        $process->mustRun();

        $output->writeln('Inas has been stopped');

        return 0;
    }
}
