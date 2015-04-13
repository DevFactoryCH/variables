<?php namespace Devfactory\Variables\Composers;

class VariablesComposer {

  public function compose($view) {
    $layout = (object) [
      'extends' => config('variables.config.layout.extends'),
      'header' => config('variables.config.layout.header'),
      'content' => config('variables.config.layout.content'),
    ];

    $view->with(['layout' => $layout]);
  }

}