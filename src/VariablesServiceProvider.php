<?php namespace Devfactory\Variables;

use Illuminate\Support\ServiceProvider;

use Devfactory\Variables\Models\Variable;

class VariablesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
    $this->loadViewsFrom(__DIR__ . '/views', 'variables');
    $this->loadTranslationsFrom(__DIR__ . '/lang', 'variables');

    $this->publishViews();
    $this->publishConfig();
    $this->publishMigration();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
    $this->mergeConfigFrom(
      __DIR__ . '/config/config.php', 'variables.config'
    );
    $this->mergeConfigFrom(
      __DIR__ . '/config/variables.php', 'variables.list'
    );

    $this->app->singleton('variables', function ($app) {
      return new Variables(new Variable());
    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array('variables');
	}

  /**
   * Publish the package views
   */
  protected function publishViews() {
    $this->publishes([
        __DIR__ . '/views' => base_path('resources/views/vendor/variables'),
    ], 'views');
  }

  /**
   * Publish the package configuration
   */
  protected function publishConfig() {
    $this->publishes([
      __DIR__ . '/config/config.php' => config_path('variables.config.php'),
      __DIR__ . '/config/variables.php' => config_path('variables.list.php'),
    ], 'config');
  }

  /**
   * Publish the migration stub
   */
  protected function publishMigration() {
    $this->publishes([
      __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
    ], 'migrations');
  }

}
