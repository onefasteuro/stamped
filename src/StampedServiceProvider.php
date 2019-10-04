<?php

namespace onefasteuro\Stamped;

use Illuminate\Support\ServiceProvider;

class StampedServiceProvider extends ServiceProvider
{
	
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Publishing is only necessary when using the CLI.
		if ($this->app->runningInConsole()) {
			$this->bootForConsole();
		}
	}
	
	
	
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/stamped.php', 'stamped');
		
		$this->app->singleton(Stamped::class, function($app){
			
			$config = $app['config']->get('stamped');
			return new Stamped($config);
			
		});
		
	}
	
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['stamped'];
	}
	
	/**
	 * Console-specific booting.
	 *
	 * @return void
	 */
	protected function bootForConsole()
	{
		// Publishing the configuration file.
		$this->publishes([
			__DIR__ . '/../config/stamped.php' => config_path('stamped.php'),
		], 'stamped.config');
		
		$this->commands([
		
		]);
	}

}
