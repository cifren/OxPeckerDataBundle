<?php

namespace Earls\OxPeckerDataBundle\Command;

use Pp3\DataTierBundle\Reports\BaseReport;
use Earls\OxPeckerDataBundle\Database\ConnectionAdapter;
use Monolog\Logger;

/**
 * BaseCommand  Base class for all Command objects
 *
 * @author  Dave Meikle
 * @date    2014-05-21
 */
abstract class BaseCommand
{
    protected $connection = null;

    protected $logger = null;

    protected $report = null;

    public function __construct(ConnectionAdapter $connection, BaseReport $report, Logger $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
        $this->report = $report;
    }

    /**
     * main entry point for the class
     */
    abstract public function execute(array $params);
}
