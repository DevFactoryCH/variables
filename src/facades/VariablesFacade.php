<?php namespace Devfactory\Variables\Facades;

use Illuminate\Support\Facades\Facade;

class VariablesFacade extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'variables'; }

}