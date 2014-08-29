Dev Documentation
=================

Here some explanation about the tool



Override
========

This tool use Symfony2, so quickly you can overwrite via 2 solutions :

First will be to override the bundle and rewrite the Class you want to modify, 
see [How to Use Bundle Inheritance to Override Parts of a Bundle ](http://symfony.com/doc/current/cookbook/bundles/inheritance.html)

Second will be to use services and override the id :

For example if you desire to change the behaviour of `DataSourceManager`: 
    in your project, services.yml

```yaml
services:            
    oxpecker.data.datasource.manager:
        #your own class
        class:      YourProject\AcmeBundle\Model\DataSourceManager
        arguments:
            - "@doctrine.orm.entity_manager"
            - "@logger"
            - "@newClassArgument"

```

Fast and easy, ready to go !!

