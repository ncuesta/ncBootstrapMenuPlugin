<?php

/*
 * Bootstrap-based menu helper.
 *
 * Typical use will be calling the `ncbt_menu' helper function with some optional arguments:
 *
 * <code>
 *   // Render the menu from {sf_config_dir}/menu.yml
 *   <?php echo ncbt_menu(); ?>
 *
 *   // Render the menu from a custom config file
 *   <?php echo ncbt_menu('/path/to/some/config/file.yml'); ?>
 *
 *   // Render two menus: a primary and a secondary ones
 *   <?php echo ncbt_menu(); ?> // Primary menu
 *   <?php echo ncbt_menu('/path/to/secondary-menu.yml', array('secondary' => true)); ?>
 * </code>
 *
 * @see    http://twitter.github.com/bootstrap/javascript.html#dropdown
 *
 * @author José Nahuel Cuesta Luengo <nahuelcuestaluengo@gmail.com>
 */

/**
 * Get the HTML snippet for a Bootstrap Menu.
 *
 * @param string $config  An optional path to the config file. Defaults to {sf_config_dir}/menu.yml
 * @param array  $options An optional array of options. Currently only 'secondary' is supported.
 *
 * @return string
 */
function ncbt_menu($config = null, $options = array())
{
  if (null === $config)
  {
    $config = sfConfig::get('sf_config_dir') . '/menu.yml';
  }

  $config = new ncbtMenuConfigParser($config, $options);

  return strval($config->getMenu());
}

/**
 * Helper function to render bootstrap menu items manually.
 * This function starts a menu item's markup.
 *
 * @param bool   $with_children Whether the menu item will have children.
 * @param string $extra_classes Optional extra CSS classes for the item.
 *
 * @return string
 */
function ncbt_menu_item_start($with_children = false, $extra_classes = '')
{
  $html = '<li';

  if ($with_children)
  {
    $html .= ' data-dropdown="dropdown" class="dropdown ' . $extra_classes . '"';
  }
  else
  {
    $html .= ' class="' . $extra_classes . '"';
  }

  return $html.'>';
}

/**
 * Helper function to render bootstrap menu items manually.
 * This function ends a menu item's markup.
 *
 * @return string
 */
function ncbt_menu_item_end()
{
  return '</li>';
}

/**
 * Helper function to render a bootstrap menu item's dropdown toggle manually.
 *
 * @param  string $title         The title for the dropdown toggle.
 * @param  string $extra_classes Optional extra CSS classes for the dropdown toggle.
 *
 * @return string
 */
function ncbt_menu_item_dropdown_toggle($title, $extra_classes = '')
{
  return content_tag('a', $title, array('href' => '#', 'class' => 'dropdown-toggle ' . $extra_classes));
}

/**
 * Helper function to render a bootstrap menu item's dropdown content manually.
 * This function starts the dropdown content's markup.
 *
 * @return string
 */
function ncbt_menu_item_dropdown_start()
{
  return '<ul class="dropdown-menu">';
}

/**
 * Helper function to render a bootstrap menu item's dropdown content manually.
 * This function ends the dropdown content's markup.
 *
 * @return string
 */
function ncbt_menu_item_dropdown_end()
{
  return '</ul>';
}