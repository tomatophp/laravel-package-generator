<?php

namespace TomatoPHP\LaravelPackageGenerator;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\LaravelPackageGenerator\Console\FilamentPluginGenerator;
use TomatoPHP\LaravelPackageGenerator\Console\FilamentResourceClassesGenerator;
use TomatoPHP\LaravelPackageGenerator\Console\LaravelPackageGenerator;

class LaravelPackageGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-package-generator.php', 'laravel-package-generator');

        //Publish Config
        $this->publishes([
            __DIR__.'/../config/laravel-package-generator.php' => config_path('laravel-package-generator.php'),
        ], 'config');

        //Publish Stubs
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/laravel-package-generator'),
        ], 'stubs');

        //Register generate command
        $this->commands([
            LaravelPackageGenerator::class,
            FilamentPluginGenerator::class,
            FilamentResourceClassesGenerator::class
        ]);
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
