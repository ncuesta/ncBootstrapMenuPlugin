<?php

/**
 * ncbtMenuConfigParser class.
 *
 * @author José Nahuel Cuesta Luengo <nahuelcuestaluengo@gmail.com>
 */
class ncbtMenuConfigParser
{
  protected $source     = null;
  protected $menu       = null;
  protected $root_items = array();
  protected $options    = array();

  public function __construct($source, $options = array())
  {
    $this->options = $options ?: array();

    if (is_string($source))
    {
      $this->source = sfYaml::load($source);
    }
    else
    {
      $this->source = $source;
    }

    $this->parse();
  }

  /**
   * Parse the given configuration.
   *
   * @throws RuntimeException
   */
  protected function parse()
  {
    if (!is_array($this->source))
    {
      throw new RuntimeException('Unable to parse source: ' . strval($this->source));
    }

    if (sfContext::hasInstance())
    {
      $user = sfContext::getInstance()->getUser();
    }
    else
    {
      // Get a mock user if no real user is available
      $user = new sfBasicSecurityUser();
    }

    $this->root_items = array();

    $working_set = $this->source;
    $working_set = array_shift($working_set);

    foreach ($working_set as $key => $root_element)
    {
      $config    = new ncbtMenuItemConfig($key, $root_element);
      $root_item = ncbtMenuItem::createFromConfig($config, $user);

      if (null !== $root_item)
      {
        $this->root_items[] = $root_item;
      }
    }
  }

  /**
   * Get the menu from the provided configuration.
   *
   * @return ncbtMenu
   */
  public function getMenu()
  {
    if (null === $this->menu)
    {
      $secondary = isset($this->options['secondary']) && $this->options['secondary'];
      $this->menu = new ncbtMenu($this->root_items, $secondary);
    }

    return $this->menu;
  }
}