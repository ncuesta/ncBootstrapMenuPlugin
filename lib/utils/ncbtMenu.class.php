<?php

/**
 * ncbtMenu class.
 *
 * @author José Nahuel Cuesta Luengo <nahuelcuestaluengo@gmail.com>
 */
class ncbtMenu
{
  protected $items = array();
  protected $secondary = false;

  public function __construct(array $items, $secondary = false)
  {
    $this->items = $items;
    $this->secondary = $secondary;
  }

  public function renderItems()
  {
    $html = '';

    foreach ($this->items as $item)
    {
      $html .= strval($item);
    }

    return $html;
  }

  public function __toString()
  {
    $secondary = $this->secondary ? 'secondary-' : '';

    return <<<HTML
<ul class="{$secondary}nav">{$this->renderItems()}</ul>
HTML;
  }
}