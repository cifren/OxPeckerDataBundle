Overview
========

This Symfony2 bundle aims to provide commands to build your own report data, simply, organized and quickly. It can as well be used for importing data from one system to another.
OxPeckerData use a system of ETL via 2 solutions. 
First solution is using SQL, which give you a real fast and powerful tool to extract, transform and load a big amount of data, easily but it is limited to Sql functions. Oxpecker allow to organize quickly your commands.
Second solution is to use ETL using PHP, this method gives you unlimited possibility but it is kind of slow when you have to iterate each line of your data. This method is using ETLknpBundle.

The idea is to:

1. Create a configuration file defining Query you want use for the import / delete data. (SQL lqyer)
2. Apply an ETL on the top of it. (PHP layer)
2. Launch the command manualy/via cron task, with parameters or not.

Theory
======

Documentation
=============

For installation and how to use the bundle refer to [Resources/doc/index.md](https://github.com/Earls/OxPeckerDataBundle/blob/master/Resources/doc/index.md)
