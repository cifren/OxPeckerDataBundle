In your config you can create a function `setParamsMapping()`.

This will help you to define better your arguments.


In your `Configuration file` :

```php
//...
class FirstReportDataConfiguration extends DataConfiguration
{
    //...
    public function setParamsMapping()
    {
        $mapping = array(
            'recipe_id' => 15, //you can default values
            'week_id' => null,
            'plating_id' => null,
        );

        return $mapping;
    }
    
    public function getETLProcesses(Context $context)
    {
        $args = $context->getArgs();
        
        //by default it will be 15, unless you specified in your command call
        $recipeId = $args['recipe_id']; 
        //...    
    }
    //...
}

```

when you call your command
```shell
#example with param:
php app/console oxpecker:import datatier.recipe recipe_id=1

```

`setParamsMapping()` function can return :
- `null` means no arguments specification
- empty `array()` means no arguments will be accepted
- `array(values)` means only values specified will be accepted

If you want to see mappings from the command call
```shell
#example help command:
php app/console oxpecker:import datatier.recipe help
#this command will display a help for the command and give you argument specifications
```
