Flamingo usage
==============

For more information about Flamingo you can go on the documentation 
[FlamingoCommandQueue](https://github.com/Earls/FlamingoCommandQueue/blob/master/README.md)

If you use it, you will have to install it 
[Flamingo installation](https://github.com/Earls/FlamingoCommandQueue/blob/master/Resources/doc/installation.md)

Enable flamingo in your configuration:

```php
//...
class IngredientUsageDataConfiguration extends DataConfiguration
{
    //...
    protected function setOptions()
    {
        return array(
            //just add this to your config
            'activate-flamingo' => true,
        );
    }
    //...
}
```

If you want to add more options

```php
<?php
//...
class IngredientUsageDataConfiguration extends DataConfiguration
{
    //...
    protected function setOptions()
    {
        return array(
            //just add this to your config
            'activate-flamingo' => array(
                //how many max instance do you want to queue
                'maxPendingInstance' => 30, //integer
                //how long do you want the instance to wait for the next check
                'pendingLapsTime' => 60, //integer   
            ),
        );
    }
    //...
}
```

You can as well setup 2 other options for Queuing in Flamingo

```php
<?php
//...
class IngredientUsageDataConfiguration extends DataConfiguration
{
    //...
    
    
    /**
     * name of the group of command
     * All commands with the same group will be queued
     * @param string        Name come from the service name defined earlier
     * @return string       Name of the group
     **/
    public function setQueueGroupName($name, array $args)
    {
        return null;
    }

    /**
     * unique id for the command, this will be usually based on a name and arguments
     * For example you want to Queue all command with argument store_id is unique
     *  $uniqueId = $name.'storeId='.$args['storeId'];
     *  return uniqueId;
     *
     *  The command will queue only one command with this Id, avoid repetition, 
     * if you add the command on web page and the command is ran several times per minutes
     * or if the command is lasting slower than the time between 2 crons, your call, 
     * or if you need a permanent execution etc...
     *
     * @return string       A unique id for this type of command
     **/
    public function setQueueUniqueId($name, array $args)
    {
        return null;
    }
    //...
}
```
