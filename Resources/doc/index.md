Installation
============

First install the lib [OxPeckerData](https://github.com/Earls/OxPeckerData/blob/master/lib/Earls/OxPeckerData/Doc/index.md)

Add the bunde to your `composer.json` file:
```json
require: {
    // ...
    "earls/oxpecker-data-bundle": "dev-master",
    // ...
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/earls/OxPeckerDataBundle.git"
    }
]
```

Then run a `composer update`:
```shell
composer.phar update
# OR
composer.phar update earls/oxpecker-data-bundle # to only update the bundle
```

Register the bundle with your `kernel`:
```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Earls\OxPeckerDataBundle\EarlsOxPeckerDataBundle(),
    // ...
);
```

Your first report table
=================

Here a simple example of `report table` creation. 

In this Example, we will display an ingredient list (this example is really basic but can be replicate on more complicated case with a lot of join table).

You need tables structure :
  
  Table `ingredient`:

    id  |Ing_name   |fk_recipe_id
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
    id      |recipe_id |ing_id
    --------------------------
    null    |null      |


The folder structure for the example :

```
-- src/
---- Project/
------ DataTierBundle/
-------- ReportData/
---------- FirstReportData.php
```

Config file
========
Create your `reportData file` :

```php

namespace Project\DataTierBundle\ReportData;

use Earls\OxPeckerData\Database\Query;

/**
 *  Setup your query or handler to manage your work on report table
 
 *  Project\DataTierBundle\ReportData\FirstReportData
 */
class FirstReportData extends ReportData
{

    //this function will be launched everytime you launch the sf2 command oxPecker:import
    public function getImport(array $params)
    {
        $where = null;
        $pdoParams = array();
        
        if (isset($params['recipe_id'])) {
            $where .= $where ? 'WHERE ' : null;
            $where .= "recipe_id = :recipe ";
            $pdoParams['recipe'] = $params['recipe_id'];
        }
        
        $query = 'INSERT INTO rpt_recipe_ing 
                    SELECT r.id, i.id FROM recipe r JOIN ingredient i ON r.id = i.recipe_id ' . $where;
        $query = new Query($query, $pdoParams);
        
        //return can be a handler or a query object
        return $query;
    }
    
    //this function will be launch everytime you launch the sf2 command oxPecker:delete
    public function getDelete(array $params)
    {
        $where = null;
        $pdoParams = array();
        
        if (!isset($params['recipe_id'])) {
            throw new \Exception('Required param recipe_id');
        }
        
        $query = 'DELETE rpt_recipe_ing WHERE recipe_id = :recipe';
        $pdoParams['recipe'] = $params['recipe_id'];
        $query = new Query($query, $pdoParams);
        
        //return can be a handler or a query object
        return $query;
    }
    
    //if this function return null, all arguments are allow, 
    //if return empty array, no arguments are allow
    //if return array with value, only given arguments will be allow
    //any other arguments in the two last case will be ignore
    public function setParamsMapping()
    {
        $mapping = array(
            //recipe_id is the param name you will pass later to your command, null is the default value
            'recipe_id' => null,
        );

        return $mapping;
    }
}
```

This class will be the core of your dataReport which will build your report table `rpt_recipe_ing`


Create your service:
```yaml
parameters:
    datatier.recipe.class:      Project\DataTierBundle\ReportData\FirstReportData

services:
    datatier.recipe:
        class:      %datatier.recipe.class%
```

Handler in your reportData
==========================

You can as well create a handler in order to manage your work:

in `Project\DataTierBundle\ReportData\FirstReportData`
```php
    use Project\DataTierBundle\\Handlers\RecipeHandler;
    
    public __construct($connection)
    {
        $this->connection = $connection;
    }
    
    public function getImport()
    {
        return new recipeHandler($this->connection);
    }
```

your handler `Project\DataTierBundle\Handlers\RecipeHandler` 
```php

namespace Project\DataTierBundle\Handlers;

use Earls\OxPeckerData\Handler\DataHandlerInterface;

/**
 *  Excute all your code for your import here
 *
 *  Project\DataTierBundle\Handlers\RecipeHandler
 */
class RecipeHandler extends DataHandlerInterface
{
    public function __construct($connection, array $params)
    {
        $this->connection = $connection;
        $this->params = $params;
    }
    
    //this function will be executed by the sf2 command
    public function execute()
    {
        $where = null;
        $pdoParams = array();
        
        if (isset($params['recipe_id'])) {
            $where .= $where ? 'WHERE ' : null;
            $where .= "recipe_id = :recipe ";
            $pdoParams['recipe'] = $params['recipe_id'];
        }
        
        $query = 'INSERT INTO rpt_recipe_ing 
                    SELECT r.id, i.id FROM recipe r JOIN ingredient i ON r.id = i.recipe_id ' . $where;
        $query = new Query($query, $pdoParams);
        
        $this->executeQuery($query);
    }

    protected function executeQuery(Query $query)
    {
        $stmt = $this->connection->prepare($query->getSql());

        foreach($query->getParams() as $key => $value){
            $stmt->bindValue(":$key", $value, \PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt;
    }
}
```

your service:
```yaml
    datatier.recipe:
        class:      %datatier.recipe.class%
        #@connection can be any connection, can be doctrine, you have to adapt your code with it
        arguments: ["@connection"]
```

Command
=======

From Symfony2 you can run 2 commands :
```shell
#usage
php app/console oxpecker:import service_name args

#example without param: (for a cron job for example)
php app/console oxpecker:import datatier.recipe

#example with param:
php app/console oxpecker:import datatier.recipe recipe_id=1

```

```shell
#usage
php app/console oxpecker:import service_name args

#example without param: (in this example this command will trigger an exception)
php app/console oxpecker:delete datatier.recipe

#example with param:
php app/console oxpecker:delete datatier.recipe recipe_id=1