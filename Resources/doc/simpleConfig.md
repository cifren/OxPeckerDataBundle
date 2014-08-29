Your first report table
=================

Here a simple example of `report table` creation. 

In this Example, we will display an ingredient list (this example is really basic but can be replicate on more complicated case.

You need tables structure :
  
  Table `ingredient`:

    id  |ing_name   |fk_recipe_id
    ----|-----------|------------
    1   |asparag    |1
    2   |salt       |1
    3   |olive      |2
    
  Table `recipe`:

    id  |recipe_name
    ----|-----------
    1   |aspa plate    
    2   |olive plate    
    
  Table `rpt_recipe_ing`:
  
    id      |rpt_recipe_name |rpt_ingredient_name
    --------|----------------|-------------------
    null    |null            |null


The folder structure for the example :

```
-- src/
---- Project/
------ Entity
-------- RptRecipeIng
------ DataTierBundle/
-------- OxPeckerConfiguration/
---------- FirstReportDataConfiguration.php
```

First create your entity `RptRecipeIng` based on the structure of the table `rpt_recipe_ing`

Config file
========
Create your `FirstReportDataConfiguration file` :

```php

namespace Project\DataTierBundle\OxPeckerConfiguration;

use Earls\OxPeckerDataBundle\Definition\DataConfiguration;
use Earls\OxPeckerDataBundle\Definition\Context;

/**
 *  Setup config
 
 *  Project\DataTierBundle\OxPeckerConfiguration\FirstReportDataConfiguration
 */
class FirstReportDataConfiguration extends DataConfiguration
{

    public function preProcess(Context $context)
    {
        //what you want
    }
    
    /**
     * authorized arguments
     *
     * @return array
     */
    public function getETLProcesses(Context $context)
    {
        $etlProcesses = array(
            new SqlETLProcess(
                'SELECT ing_name, recipe_name '. //Transformation
                'FROM ingredient i JOIN recipe r ON i.fk_recipe_id = r.id', //FROM: Extraction
                'RptRecipeIng', //TO : Load, where you want to send the data
                array('rptRecipeName', 'rptIngredientName') //MAPPING: From the SELECT statement to the entity, matching fields from SQL statement to the entity
            ),
        );

        return $etlProcesses;
    }
    
    /**
     * authorized arguments
     *
     * @return array
     */
    public function postProcess(Context $context)
    {
        //what you want
    }
}
```

And that's it your config is done.

In the constructor of `SqlETLProcess` you can use `Raw sql` statment, `Doctrine\ORM\Query` or `Doctrine\DBAL\Query\QueryBuilder`.

As you can see, **ETL** is still used :
- **Extraction** : By the FROM
- **Transformation** : By the SELECT
- **Load** : By The entity

##### *WARNING*:

    When the system will call `SqlETLProcess` the system will drop automatically the entity table and recreate this table. If you don't want this default behaviour, just add the option `dropOnInit`
    
```php 
    new SqlETLProcess(
            'SELECT ing_name, recipe_name 
             FROM ingredient i JOIN recipe r ON i.fk_recipe_id = r.id', 
            'RptRecipeIng', 
            array('rptRecipeName', 'rptIngredientName'), 
            array('dropOnInit' => false)
        ),
```


Create your service:
```yaml
parameters:
    datatier.recipe.class:      Project\DataTierBundle\OxPeckerConfiguration\FirstReportDataConfiguration

services:
    datatier.recipe:
        class:      %datatier.recipe.class%
```

The name of your service `datatier.recipe`, will be the key of the call from the console.

Command
=======

From Symfony2 you can run your command which will use your config :
```shell
#generic usage
php app/console oxpecker:import service_name args
```
- service_name: it is the name of your service, in our case it is `datatier.recipe`

- args : it is the list of your arguments you want to send to your command, for example 
if you choose to give the Id of an ingredient, `ingredient_id=1582` for more details 
see [doc arguments](arguments.md)

Here some example of calls :

```shell
#example without param: (for a cron job for example)
php app/console oxpecker:run datatier.recipe

#example with param:
php app/console oxpecker:run datatier.recipe recipe_id=1

#example for help:
php app/console oxpecker:run datatier.recipe help

```
