<?php

namespace Earls\OxPeckerDataBundle\Core;

use Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface;
use Earls\OxPeckerDataBundle\Model\DataSourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Earls\OxPeckerDataBundle\ETL\Loader\Doctrine\ORMLoader;
use Earls\OxPeckerDataBundle\ETL\Transformer\Doctrine\ObjectToObjectTransformer;
use Knp\ETL\Context\Context;
use Earls\OxPeckerDataBundle\Core\ETLProcess;
use Doctrine\ORM\EntityManager;

/**
 * Earls\OxPeckerDataBundle\Core\DataProcess
 */
class DataProcess
{

    protected $entityManager;

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * 
     * @param \Earls\OxPeckerDataBundle\Definition\DataConfigurationInterface $config
     * @param array $params
     * 
     * @return null
     */
    public function process(DataConfigurationInterface $config, array $params)
    {
        $this->setEntityManager($config->getEntityManager());
        $dataSources = $config->defineDataSources($params);
        if (!$dataSources instanceof ArrayCollection) {
            throw new \Exception('DataSources is not an array');
        }
        $this->processFirstLayer($dataSources);

        $reportTransformers = $config->defineReportTransformers();
        if (!$reportTransformers instanceof ArrayCollection) {
            throw new \Exception('ReportTransformers is not an array');
        }
        $this->processSecondLayer($reportTransformers);
    }

    protected function processFirstLayer(ArrayCollection $dataSources)
    {
        $loader = new ORMLoader($this->getEntityManager());
        $context = new Context();
        foreach ($dataSources as $dataSource) {
            if (!$dataSource instanceof DataSourceInterface) {
                throw new \Exception(sprintf('DataSource is not an instance of "%s"', 'Earls\OxPeckerDataBundle\Model\DataSourceInterface'));
            }

            $etl = new ETLProcess();            
            $etl->setContext($context)
                    ->setExtractor($dataSource->getExtractor())
                    ->setTransformers($dataSource->getTransformers())
                    ->setLoader($loader);
            $etl->process();
        }
        
        var_dump('end first phase');
        die();
    }

    protected function processSecondLayer(ArrayCollection $reportTransformers)
    {
        $loader = new ORMLoader();
        $context = new Context();
        $extractor = new Context();

        $etl = new ETLProcess();
        $etl->setContext($context)
                ->setExtractor($extractor)
                ->setTransformers($reportTransformers)
                ->setLoader($loader);

        $etl->process();
    }

}
