<?php

namespace Earls\OxPeckerDataBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface;
use Fuller\FullerGroupBundle\Command\Common\Classes\AdvancedCommand;

/**
 * Earls\OxPeckerDataBundle\Command\RunCommand
 */
class RunCommand extends AdvancedCommand
{

    protected function configure()
    {
        parent::configure();
        $this
                ->setName('oxpecker:run')
                ->setDescription('Run your data tier config, generate easily your data for report or data center or import')
                ->addArgument('namedatatier', InputArgument::REQUIRED, 'Which data tier config do you want execute')
                ->addArgument('args', InputArgument::IS_ARRAY, 'Add all arguments this command needs');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setStartTime();
        $container = $this->getContainer();

        $this->getLogger()->notice("Process {$input->getArgument('namedatatier')}");
        $dataTierConfig = $container->get($input->getArgument('namedatatier'));
        $dataTierConfig->setLogger($this->getLogger());
        if (!$dataTierConfig) {
            throw new \InvalidArgumentException(sprintf('No data tier configuration has been find with name \'%s\' ', $input->getArgument('namedatatier')));
        } elseIf (!$dataTierConfig instanceof DataConfigurationInterface) {
            throw new \InvalidArgumentException(sprintf('Service has been found but the class is not an instance of \'Earls\OxPeckerData\Report\SQLInterface\''));
        }

        if (isset($input->getArgument('args')[0]) && $input->getArgument('args')[0] == 'help') {
            $this->helpDisplay($input->getArgument('namedatatier'), $dataTierConfig->setParamsMapping(), $output);
            return true;
        }

        $dataProcess = $this->getContainer()->get('oxpecker.data.process');
        $dataProcess->setLogger($this->getLogger());

        $args = $this->formatArguments($dataTierConfig->setParamsMapping(), $input->getArgument('args'));
        $dataProcess->process($dataTierConfig, $args);

        $this->setEndTime();
        $this->noticeTime();

        return true;
    }

    /**
     * Explicit arguments for a selected config, 
     * 
     * @param string $name
     * @param array|null $mapping
     * @param OutputInterface $output
     */
    protected function helpDisplay($name, $mapping, OutputInterface $output)
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
    }

    /**
     * Format arguments in order to contain default from config and return an array of arguments from the input
     * 
     * If mapping is null, means no arguments required
     * If mapping is an empty array, means no arguments required and throw issue if there is
     * If mapping is an array, system will control each argument, throw issue if argument not in the list come from input
     * 
     * @param array $mappingArgs
     * @param array $args
     * @return array
     * 
     * @throws \Exception
     */
    protected function formatArguments(array $mappingArgs = null, array $args)
    {
        $formatedArgs = $mappingArgs;
        foreach ($args as $arg) {
            $argumentExploded = explode('=', $arg);
            //ignore argument without '=' sign
            if (count($argumentExploded) < 2) {
                continue;
            }
            if (is_array($mappingArgs) && !in_array($argumentExploded[0], array_keys($mappingArgs))) {
                throw new \Exception("The argument '{$argumentExploded[0]}' is not part of the mapping defined in configuration");
            }
            $formatedArgs[$argumentExploded[0]] = $argumentExploded[1];
        }

        return $formatedArgs;
    }

}
