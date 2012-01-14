# ncBootstrapMenuHelper

[symfony 1.4](http://symfony-project.com/) helper for rendering HTML menus that are based on Twitter's bootstrap CSS + JS library.

## Usage

Typical use will be including the `BootstrapMenu` helper and calling the `ncbt_menu()` helper function with some optional arguments:

```php
<?php use_helper('BootstrapMenu'); ?>

<!-- Render the menu from {sf_config_dir}/menu.yml -->
<?php echo ncbt_menu(); ?>

<!-- Render the menu from a custom config file -->
<?php echo ncbt_menu('/path/to/some/config/file.yml'); ?>

<!-- Render two menus: a primary and a secondary ones -->
<!-- Primary menu: -->
<?php echo ncbt_menu(); ?>
<!-- Secondary menu: -->
<?php echo ncbt_menu('/path/to/secondary-menu.yml', array('secondary' => true)); ?>
```

## About Twitter's bootstrap

Bootstrap is an awesome CSS + JS framework. [Check it out](http://twitter.github.com/bootstrap/javascript.html#dropdown) if you haven't already!

## License

This plugin is licensed under MIT Software License, so feel free to fork it. Pull Requests are welcome!