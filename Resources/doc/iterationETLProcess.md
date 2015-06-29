```php

        $extractor = new ORMExtractor($qb, $this->getEntityManager());
        $transformers = array(
            new ObjectAlterationTransformer(function(RptPpIngredientUsageDaily $object) {
                        $fgWeek = $this->getEntityManager()->getRepository('Pointdb:FgWeeklyCalendar')->find($object->getFgWeeklyCalendarId());
                        $fgStore = $this->getEntityManager()->getRepository('Pointdb:FgStore')->find($object->getFgStoreId());
                        $ppStockbook = $this->getEntityManager()->getRepository('Concept:PpStockbook')->find($object->getPpStockbookId());

                        $cost = $this->getEntityManager()->getRepository('Concept:SalCostIngredient')->getClosestCost($fgWeek, $fgStore, $ppStockbook);
                        $object->setPpStockbookCost($cost);
                    })
        );
        $loader = new ORMLoader($this->getEntityManager());

        new IterationETLProcess($extractor, $transformers, $loader, $this->getLogger());

```

You have to choose:
- your data Extractor
- your data Transformers
- your data Loader

And the system will take care of the rest.

You can use a function from your class with your transformer 'ObjectAlterationTransformer'
```php
    
class OxpeckerData{
    ...

    public function getETLProcesses(Context $context)
    {
        //get an array
        $extractor = new CachedExtractor($array);
        $transformers = array(
            new ObjectAlterationTransformer($this, 'transformObject')
        );
        $loader = new ORMLoader($this->getEntityManager());

        //ETL process
        $etlProcesses = array(
            new IterationETLProcess($extractor, $transformers, $loader, $this->getLogger())
        );

        return $etlProcesses;
    }

    //need to have the same argument than the closure
    protected function transformObject($object)
    {
        //transformation
        return $object;
    }

    ...
}
```

Example for ObjectToObjectTransformer
```php
      
class OxpeckerData{
    ...

    public function getETLProcesses(Context $context)
    {
        //get an array
        $extractor = new CachedExtractor($array);

        //array will have an index 'id', and the object will have the method 'setMyId()'
        $transformers = array(
            new ArrayToObjectTransformer('Entity\Cat', new DataMap(array('id' => 'myId')))
        );
        $loader = new ORMLoader($this->getEntityManager());

        //ETL process
        $etlProcesses = array(
            new IterationETLProcess($extractor, $transformers, $loader, $this->getLogger())
        );

        return $etlProcesses;
    }

    //need to have the same argument than the closure
    protected function transformObject($object)
    {
        //transformation
        return $object;
    }

    ...
}
```

Another Example of usage :
```php 
     
class OxpeckerData{
    ...

    public function getETLProcesses(Context $context)
    {
        //receive an array
        $extractor = new CachedExtractor($array);

        $transformers = array(
            new ArrayAlterationTransformer($this, 'transformArray'),
            new ArrayToObjectTransformer($this->getClassname('Concept:RptVarianceSummary'), new DataMap(array('stockbookId' => 'ppStockbookId')))
        );

        //receive an entity
        $loader = new ORMLoader($this->getEntityManager());

        //ETL process
        $etlProcesses = array(
            new IterationETLProcess($extractor, $transformers, $loader, $this->getLogger())
        );

        return $etlProcesses;
    }

    //need to have the same argument than the closure
    protected function transformArray($array)
    {
        //transformation
        return $array;
    }

    ...
}
```