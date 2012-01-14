<?php

/**
 * ncbtMenuItemConfig class.
 *
 * @author José Nahuel Cuesta Luengo <nahuelcuestaluengo@gmail.com>
 */
class ncbtMenuItemConfig
{
  protected $key_name;
  protected $config;

  public function __construct($key_name, array $config)
  {
    $this->key_name = $key_name;
    $this->config   = $config;
  }

  /**
   * Get a configuration key, with an optional default value.
   *
   * @param  string $key     The key name.
   * @param  mixed  $default The default value, returned when $key is not set.
   *
   * @return mixed
   */
  public function get($key, $default = null)
  {
    $method = 'get' . ucfirst($key);

    if (method_exists($this, $method))
    {
      $value = $this->$method();
    }
    else
    {
      $value = isset($this->config[$key]) ? $this->config[$key] : null;
    }

    return $value ?: $default;
  }

  /**
   * Get the title for the item.
   *
   * @return string
   */
  public function getTitle()
  {
    return isset($this->config['title']) ? $this->config['title'] : ucfirst($this->key_name);
  }

  /**
   * Get this config as an array.
   *
   * @return array
   */
  public function toArray()
  {
    return $this->config;
  }
}
