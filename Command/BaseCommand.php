<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerData\Report\SQLInterface;
use Earls\OxPeckerData\Database\ConnectionAdapterInterface;

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

        $dataTierConfig = $container->get($input->getArgument('namedatatier'));

        if (!$dataTierConfig) {
            throw new \InvalidArgumentException(sprintf('No data tier configuration has been find with name \'%s\' ', $input->getArgument('namedatatier')));
        } elseIf (!$dataTierConfig instanceof SQLInterface) {
            throw new \InvalidArgumentException(sprintf('Service has been found but the class is not an instance of \'Earls\OxPeckerData\Report\SQLInterface\''));
        }

        $dataBuilder = $this->getContainer()->get('oxpecker.data.builder');

        if ($dataTierConfig->getConnection() && $dataTierConfig->getConnection() instanceof ConnectionAdapterInterface) {
            $dataBuilder->setConnection($dataTierConfig->getConnection());
        }else{
            $this->getContainer()->get('oxpecker.data.connection');
        }

        $dataBuilder->execute($this->typeCommand, $dataTierConfig, $input->getArgument('args'));
    }

}
