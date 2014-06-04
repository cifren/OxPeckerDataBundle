<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Earls\OxPeckerDataBundle\Command\BaseCommand;

/**
 * Earls\OxPeckerDataBundle\Command\ImportCommand
 */
class ImportCommand extends BaseCommand
{

    protected function configure()
    {
        parent::configure();
        $this
                ->setName('oxpecker:import')
                ->setDescription('Import command from your data tier config')
                ->addArgument('namedatatier', InputArgument::REQUIRED, 'Which data tier config do you want execute')
                ->addArgument('args', InputArgument::IS_ARRAY, 'Add all arguments this command needs');
        ;
    }

    protected function getCommandType()
    {
        return 'import';
    }

}
