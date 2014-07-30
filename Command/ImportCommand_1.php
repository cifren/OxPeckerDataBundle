<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerData\Report\SQLInterface;
use Earls\OxPeckerData\Database\ConnectionAdapterInterface;

/**
 * Earls\OxPeckerDataBundle\Command\ImportCommand
 */
class ImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        parent::configure();
        $this
                ->setName('oxpecker:run')
                ->setDescription('Run your data tier config')
                ->addArgument('namedatatier', InputArgument::REQUIRED, 'Which data tier config do you want execute')
                ->addArgument('args', InputArgument::IS_ARRAY, 'Add all arguments this command needs');
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();

        $dataTierConfig = $container->get($input->getArgument('namedatatier'));
        if (!$dataTierConfig) {
            throw new \InvalidArgumentException(sprintf('No data tier configuration has been find with name \'%s\' ', $input->getArgument('namedatatier')));
        } elseIf (!$dataTierConfig instanceof SQLInterface) {
            throw new \InvalidArgumentException(sprintf('Service has been found but the class is not an instance of \'Earls\OxPeckerData\Report\SQLInterface\''));
        }

        if (isset($input->getArgument('args')[0]) && $input->getArgument('args')[0] == 'help') {
            $this->helpDisplay($input->getArgument('namedatatier'), $dataTierConfig->setParamsMapping(), $output);
            return true;
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

        return true;
    }

    protected function helpDisplay($name, $mapping, $output)
    {
        $parameterArgumentMessages = array();
        if (!is_array($mapping)) {
            $parametersUsage = "[]";
            $parameterArgumentMessages[]['column1'] = 'No information are available';
        } elseif (count($mapping) <= 0) {
            $parametersUsage = null;
            $parameterArgumentMessages[]['column1'] = 'No arguments are allow';
        } else {
            $msgParametersUsage = implode('=string ', array_keys($mapping)) . '=string';
            $i = 0;
            foreach ($mapping as $key => $map) {
                $parameterArgumentMessages[$i]['column1'] = "<fg=green>$key</fg=green>";
                $parameterArgumentMessages[$i]['column2'] = $map ? "<fg=yellow>default: $map</fg=yellow>" : null;
                $i++;
            }
        }

        $output->writeln("<fg=yellow>Usage</fg=yellow>");
        $output->writeln(" {$this->getName()} $name $msgParametersUsage");
        $output->writeln("");
        $output->writeln("<fg=yellow>Arguments</fg=yellow>");
        foreach ($parameterArgumentMessages as $parameterArgumentMessage) {
            $message = " {$parameterArgumentMessage['column1']}";
            $message.= isset($parameterArgumentMessage['column2']) ? "      " . $parameterArgumentMessage['column2'] : null;
            $output->writeln($message);
        }
        $output->writeln("");

        return;
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
