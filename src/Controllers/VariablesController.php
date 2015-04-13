<?php namespace Devfactory\Variables\Controllers;

use Config;
use Input;
use Lang;
use Redirect;
use URL;
use View;

use \Variables;

use Illuminate\Routing\Controller as BaseController;

class VariablesController extends BaseController {

  /**
   * Initializer.
   *
   * @return void
   */
  public function __construct() {
    View::composer('variables::*', 'Devfactory\Variables\Composers\VariablesComposer');
  }

  /**
   * Show a list of all the variables.
   *
   * @return View
   */
  public function getIndex() {
    $variables = Variables::getAll();

    URL::setRootControllerNamespace('\Devfactory\Variables\Controllers');
    $edit_url = action('VariablesController@postUpdate');

    return View::make('variables::index', compact('variables', 'edit_url'));
  }

  /**
   * Update all the variables according to the value set in the form
   *
   * @return
   */
  public function postUpdate() {
    $variables = Variables::getAll();

    foreach ($variables as $key => $variable) {
      if (Input::get($key) != $variable['value']) {
        Variables::set($key, Input::get($key));
      }
    }

    URL::setRootControllerNamespace('\Devfactory\Variables\Controllers');

    return Redirect::to(action('VariablesController@getIndex'));
  }

}