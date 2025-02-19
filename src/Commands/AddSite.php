<?php

namespace Nxu\Inas\Commands;

use Nxu\Inas\Config\Config;
use Nxu\Inas\Config\Helper;
use Nxu\Inas\Config\InstalledSite;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'add',
    description: 'Create a virtual host config for the current directory',
)]
class AddSite extends Command
{
    protected function configure(): void
    {
        $this->addArgument(
            name: 'php',
            mode: InputArgument::REQUIRED,
            description: 'The PHP version to use (5.2, 5.6 or 7.1)',
            suggestedValues: ['5.2', '5.6', '7.1'],
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($error = Helper::ensureInstalled()) {
            $output->writeln($error);

            return 255;
        }

        if (! in_array($php = $input->getArgument('php'), ['5.2', '5.6', '7.1'])) {
            $output->writeln('<error>Invalid php version (5.2, 5.6 and 7.1 supported)</error>');

            return 1;
        }

        $config = Config::read(Helper::configFile());

        $cwd = getcwd();

        if ($config->hasSite($name = Helper::getSiteName($cwd))) {
            $output->writeln('<error>Site already exists</error>');

            return 2;
        }

        $config->sites[] = new InstalledSite(
            name: $name,
            baseDir: $cwd,
            docroot: is_dir($cwd.DIRECTORY_SEPARATOR.'public') ? 'public' : null,
            php: $php,
        );

        $config->save(Helper::configFile());

        $output->writeln("<info>Config for site $name.test has been successfully created.</info>");

        return 0;
    }

    private function vhostConfig(string $baseDir, ?string $docRoot = null): string
    {
        $docRoot = empty($docRoot) ? '' : ('/'.ltrim($docRoot, '/'));

        return <<<ENDCONF
<VirtualHost *:80>
	ServerName $baseDir.test
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/html/$baseDir$docRoot
	ErrorLog \${APACHE_LOG_DIR}/$baseDir.log
</VirtualHost>

ENDCONF;
    }
}
