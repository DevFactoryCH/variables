<?php namespace Devfactory\Variables\Composers;

use Config;

class VariablesComposer {

    public function compose($view) {
      $layout = (object) [
        'extends' => Config::get('variables::config.layout.extends'),
        'header' => Config::get('variables::config.layout.header'),
        'content' => Config::get('variables::config.layout.content'),
      ];

      $view->with(['layout' => $layout]);
    }

}