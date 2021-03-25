<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot(): void
  {
    if ($this->app->runningUnitTests()) {
      Schema::defaultStringLength(191);
    }
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register(): void
  {
    if ($this->app->environment('local', 'testing') && class_exists(DuskServiceProvider::class)) {
      $this->app->register(DuskServiceProvider::class);
    }

    if ($this->app->environment('local')) {
      $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
      $this->app->register(TelescopeServiceProvider::class);
      $this->app->register(IdeHelperServiceProvider::class);
    }
  }
}
