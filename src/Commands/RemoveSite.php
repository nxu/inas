<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Nxu\Inas\Config\InstalledSite;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'remove',
    description: 'Remove the virtual host config for the current directory',
)]
class RemoveSite extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        $config = Config::read(Helper::configFile());

        $cwd = getcwd();
        $name = $this->getSiteName($cwd);

        if (! $config->hasSite($name)) {
            $output->writeln('<error>A config for the current directory does not exist</error>');

            return 2;
        }

        $config->sites = array_filter($config->sites, fn (InstalledSite $site) => $site->name != $name);

        $config->save(Helper::configFile());

        $output->writeln("<info>Config for site $name.test has been successfully removed.</info>");

        return 0;
    }

    private function getSiteName(string $dir): string
    {
        $parts = explode(DIRECTORY_SEPARATOR, $dir);

        return end($parts);
    }
}
