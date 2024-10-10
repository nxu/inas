<?php

namespace Nxu\Inas\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'composer',
    description: 'Runs composer with the PHP version and working directory of the current project',
)]
class ExecComposer extends ExecPhp
{
}
