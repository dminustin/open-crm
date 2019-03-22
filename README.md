# OPEN CRM
Version 0.0.1

## Configuration
Use *Config/config.local.json* for local config and *Config/config.json* for global

## Migrations
All files placed at migrations/Migration_XXXXX.php
where XXXXX is unique string

I suggest to use the YYYYMMDDZZZZZ format, where ZZZZZ is number with leading zeroes

Run migration in console

**console migration**

It does not contains a downgrade migrations, i do not want to implement it in current release.


## Installation
Before installation you have to **change config files.**

* run composer install

* In command line run console install




## License
This project is licensed under the MIT License. This means you can use and modify it for free in private or commercial projects.

## Used components and scripts

[TWIG](https://github.com/twigphp/Twig) template language


