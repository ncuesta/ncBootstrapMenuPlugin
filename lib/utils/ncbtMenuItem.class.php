<?php

/**
 * ncbtMenuItem class.
 *
 * @author José Nahuel Cuesta Luengo <nahuelcuestaluengo@gmail.com>
 */
class ncbtMenuItem
{
  const
    TYPE_DIVIDER   = 'divider',
    TYPE_PARTIAL   = 'partial',
    TYPE_COMPONENT = 'component';

  protected $title    = null;
  protected $children = array();
  protected $config   = null;

  /**
   * Create a ncbtMenuItem element from a configuration array, or null if the item should not be visible.
   *
   * @static
   *
   * @param  ncbtMenuItemConfig  $config The configuration for the item.
   * @param  sfBasicSecurityUser $user   The user to test condition and/or credential against.
   *
   * @return ncbtMenuItem
   */
  static public function createFromConfig(ncbtMenuItemConfig $config, sfBasicSecurityUser $user)
  {
    if ($credentials = $config->get('credentials'))
    {
      if (false === $user->hasCredential($credentials))
      {
        return;
      }
    }

    if ($condition = $config->get('condition'))
    {
      $condition_args   = $config->get('condition_args', array());
      $condition_is_met = call_user_func_array(array($user, $condition), $condition_args);

      if (false === $condition_is_met)
      {
        return;
      }
    }

    $children = array();

    foreach ($config->get('children', array()) as $key => $child)
    {
      $child_config = new ncbtMenuItemConfig($key, $child);
      $child_item   = self::createFromConfig($child_config, $user);

      if (null !== $child_item)
      {
        $children[] = $child_item;
      }
    }

    return new self($config->get('title'), $children, $config);
  }

  public function __construct($title, array $children, ncbtMenuItemConfig $config)
  {
    $this->title    = $title;
    $this->children = $children;
    $this->config   = $config;
  }

  public function hasChildren()
  {
    return count($this->children) > 0;
  }

  public function getAttributes()
  {
    $attributes = '';

    if ($this->isDivider())
    {
      $attributes .= 'class="divider" ';
    }
    else if ($this->hasChildren())
    {
      $attributes .= 'class="dropdown" data-dropdown="dropdown" ';
    }

    return $attributes;
  }

  public function getTitleLink($attributes = array())
  {
    if ($this->isDivider())
    {
      return '';
    }
    else if ($this->hasChildren())
    {
      if (!array_key_exists('class', $attributes))
      {
        $attributes['class'] = '';
      }

      $attributes['class'] .= ' dropdown-toggle';

      return content_tag('a', __($this->title), $attributes);
    }
    else
    {
      return link_to(__($this->title), $this->target, $attributes);
    }
  }

  public function isDivider()
  {
    return $this->type === self::TYPE_DIVIDER;
  }

  public function isPartial()
  {
    return $this->type === self::TYPE_PARTIAL || substr($this->config->get('template', ''), 0, 1) === '_';
  }

  public function isComponent()
  {
    return $this->type === self::TYPE_COMPONENT || substr($this->config->get('template', ''), 0, 1) === '~';
  }

  public function childrenToString()
  {
    $html = '';

    if ($this->hasChildren())
    {
      foreach ($this->children as $child)
      {
        $html .= strval($child);
      }

      $html = <<<HTML
<ul class="dropdown-menu">{$html}</ul>
HTML;
    }

    return $html;
  }

  public function __toString()
  {
    try
    {
      if ($this->isPartial())
      {
        return get_partial(substr($this->template, 1), array('config' => $this->config->toArray()));
      }
      else if ($this->isComponent())
      {
        $template = substr($this->template, 1);

        list($module, $component) = explode('/', $template, 2);

        return get_component($module, $component, array('config' => $this->config->toArray()));
      }
    }
    catch (Exception $e)
    {
      sfContext::getInstance()->getLogger()->err('{BootstrapMenuHelper}' . $e->getMessage());

      return '';
    }

    return <<<HTML
<li {$this->getAttributes()}>
  {$this->getTitleLink()}
  {$this->childrenToString()}
</li>
HTML;
  }

  public function __get($name)
  {
    return $this->config->get($name);
  }
}