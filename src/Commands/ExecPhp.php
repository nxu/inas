<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'php',
    description: 'Runs a PHP command with the PHP version and working directory of the current project',
)]
class ExecPhp extends Command
{
    protected function configure(): void
    {
        $this->ignoreValidationErrors();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errorOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;

        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        $config = Config::read(Helper::configFile());

        $site = $config->get(Helper::getSiteName());

        if (empty($site)) {
            $output->writeln('<error>Current directory is not a valid inas project</error>');

            return 2;
        }

        $workingDir = $site->getServerPath();
        $image = 'inas-web'.str_replace('.', '', $site->php).'-1';
        $command = implode(' ', $input->getRawTokens());

        if (empty($command)) {
            $output->writeln('<error>Missing PHP command</error>');

            return 3;
        }

        $process = Process::fromShellCommandline("docker exec -w '$workingDir' -it $image $command");
        $process->setTty(true);
        $process->run();

        if ($stdout = $process->getOutput()) {
            $output->write($stdout);
        }

        if ($stderr = $process->getErrorOutput()) {
            $errorOutput->write($stderr);

            return $process->getExitCode();
        }

        return self::SUCCESS;
    }
}
