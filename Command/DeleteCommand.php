<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Earls\OxPeckerDataBundle\Command\BaseCommand;

/**
 * Earls\OxPeckerDataBundle\Command\DeleteCommand
 */
class DeleteCommand extends BaseCommand
{

    protected $typeCommand = 'delete';

    protected function configure()
    {
        parent::configure();
        $this
                ->setName('oxpecker:delete')
                ->setDescription('Import command from your data tier config')
                ->addArgument('namereport', InputArgument::REQUIRED, 'Which data tier config do you want execute')
                ->addArgument('args', InputArgument::IS_ARRAY, 'Add all arguments this command needs');
        ;
    }

}
