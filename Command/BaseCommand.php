<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerData\Report\SQLInterface;
use Earls\OxPeckerData\Database\ConnectionAdapterInterface;
use Earls\OxPeckerDataBundle\QueueManager\CommandQueue;
use Earls\OxPeckerDataBundle\QueueManager\QueueManagerInterface;

/**
 * Earls\OxPeckerDataBundle\Command\BaseCommand
 *
 * BaseCommand  Base class for all Command objects
 *
 * @author  Dave Meikle
 * @date    2014-05-21
 */
abstract class BaseCommand extends CommandQueue implements QueueManagerInterface
{
    public function executeCommand(InputInterface $input, OutputInterface $output)
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
        } else {
            $dataBuilder->setConnection($this->getContainer()->get('oxpecker.connection'));
        }

        if (!$this->getCommandType()) {
            throw new \InvalidArgumentException(sprintf('Command needs a type'));
        }

        $args = $this->formatArguments($input->getArgument('args'));

        $dataBuilder->execute($this->getCommandType(), $dataTierConfig, $args);
    }

    protected function getCommandType()
    {
        return null;
    }

    protected function formatArguments(array $args)
    {
        $formatedArgs = array();
        foreach ($args as $arg) {
            $argumentExploded = explode('=', $arg);
            //ignore argument without '=' sign
            if (count($argumentExploded) < 2) {
                continue;
            }
            $formatedArgs[$argumentExploded[0]] = $argumentExploded[1];
        }

        return $formatedArgs;
    }

}
