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
    $layout = (object) [
      'extends' => config('variables.config.layout.extends'),
      'header' => config('variables.config.layout.header'),
      'content' => config('variables.config.layout.content'),
    ];

    View::share('layout', $layout);
  }
  /**
   * Show a list of all the variables.
   *
   * @return View
   */
  public function getIndex() {
    $variables = Variables::getAll();

    $edit_url = action('\Devfactory\Variables\Controllers\VariablesController@postUpdate');

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

    return Redirect::to(action('\Devfactory\Variables\Controllers\VariablesController@getIndex'));
  }

}