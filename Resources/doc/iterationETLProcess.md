Iteration ETL Process
=====================

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
- your data Transformers, it is an array you ave to give
- your data Loader

And the system will take care of the rest.