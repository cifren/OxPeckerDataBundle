<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Earls\OxPeckerDataBundle\Command\BaseCommand
 * 
 * BaseCommand  Base class for all Command objects
 *
 * @author  Dave Meikle
 * @date    2014-05-21
 */
abstract class BaseCommand extends ContainerAwareCommand
{

    protected $typeCommand;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dataTierManager = $container->get('oxpecker.datatier.manager');

        $dataTierConfig = $dataTierManager->getDataTierConfig($input->getArgument('namedatatier'));

        if (!$dataTierConfig) {
            throw new \InvalidArgumentException(sprintf('No data tier configuration has been find with name \'%s\' ', $input->getArgument('namedatatier')));
        }

        $dataBuilder = $this->getContainer()->get('oxpecker.data.builder');

        $dataBuilder->execute($this->typeCommand, $dataTierConfig, $input->getArgument('args'));
    }

}
