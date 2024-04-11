<?php

namespace Nxu\Nap56\Commands;

use Nxu\Nap56\Config\Config;
use Nxu\Nap56\Config\Helper;
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
            name: 'docroot',
            mode: InputArgument::OPTIONAL,
            description: 'The folder inside the project directory to use as document root. E.g: public',
            suggestedValues: [
                'public',
                'public_html',
                'www',
            ],
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! is_dir(Helper::sitesFolder())) {
            $output->writeln('<error>The config folder for nap56 does not exist. Please run `nap56 install`</error>');

            return 4;
        }

        $config = Config::read(Helper::configFile());

        $baseDir = Helper::currentProject();

        if (! Helper::isValidProject($config->projectsFolder, $baseDir)) {
            $output->writeln('<error>Looks like the current folder is not actually a valid project</error>');
            $output->writeln('Your projects folder is: '.$config->projectsFolder);

            return 1;
        }

        $docroot = $input->getArgument('docroot');

        if (is_file(Helper::siteConfig($baseDir))) {
            $output->writeln("<error>Site $baseDir already exists</error>");

            return 2;
        }

        if ($docroot && ! is_dir(getcwd().DIRECTORY_SEPARATOR.$docroot)) {
            $output->writeln("<error>Docroot $docroot does not exist.</error>");

            return 3;
        }

        file_put_contents(Helper::siteConfig($baseDir), $this->vhostConfig($baseDir, $docroot));

        $output->writeln("<info>Config for site $baseDir.test has been successfully created.</info>");

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
