<?php

namespace TomatoPHP\LaravelPackageGenerator\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentPluginGenerator extends Command
{
    use RunCommand;
    use HandleFiles;
    use HandleStub;

    private string $stubPath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'package:filament';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'use this command to generate filament plugin boilerplate';

    public function __construct()
    {
        parent::__construct();
        $this->publish = __DIR__ .'/../../tomato/';
        $this->stubPath = config('laravel-package-generator.stub-path');
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $packageName = null;
        while(empty($packageName)){
            $packageName = $this->ask("Enter your package name [Like: filament-users]");
            if(is_numeric($packageName[0])){
                $this->error("Package name can't start with a number");
                $packageName = null;
            }
        }
        $packageString = Str::of($packageName);

        $packageVendor = null;
        while(empty($packageVendor)){
            $packageVendor =  $this->ask("Enter your package vendor name [Like: tomatophp]");
            if(is_numeric($packageVendor[0])){
                $this->error("Vendor name can't start with a number");
                $packageVendor = null;
            }
        }

        $packageVendorString = Str::of($packageVendor);


        $packageVendorNamespace = null;
        while(empty($packageVendorNamespace)){
            $packageVendorNamespace =  $this->ask("Enter your package vendor namespace [Like: TomatoPHP]");
            if(is_numeric($packageVendorNamespace[0])){
                $this->error("Vendor Namespace name can't start with a number");
                $packageVendorNamespace = null;
            }
        }

        $packageVendorNamespaceString = Str::of($packageVendorNamespace);

        //Package Meta
        $packageDescription = $this->ask("Enter your package description", "your package description will go here");
        $packageAuthor = $this->ask("Enter your package author", "Queen Tech");
        $packageAuthorEmail = $this->ask("Enter your package author email", "git@queentechsoltions.net");

        //Check Options
        $packageConfig = $this->ask("Has Config file? (yes/no)", "yes")?: "yes";
        $packageRoute = $this->ask("Has Routes? (yes/no)", "yes")?: "yes";
        $packageView = $this->ask("Has Views? (yes/no)", "yes")?: "yes";
        $packageMigration = $this->ask("Has Migrations? (yes/no)", "yes")?: "yes";

        $this->info('Generating package boilerplate...');

        //create package directory
        if(!File::exists(base_path(config('laravel-package-generator.packages-folder')))){
            File::makeDirectory(base_path(config('laravel-package-generator.packages-folder')));
        }

        $packageVendorPath = $packageVendorString
            ->replace(' ', '-')
            ->replace('_', '-')
            ->lower()
            ->toString();

        //Create vendor directory
        if(!File::exists(base_path(config('laravel-package-generator.packages-folder')) . "/" .$packageVendorPath)){
            File::makeDirectory(base_path(config('laravel-package-generator.packages-folder')) . "/" .$packageVendorPath);
        }

        $packageNamePath = $packageString
            ->replace(' ', '-')
            ->replace('_', '-')
            ->lower()
            ->toString();

        //Create package directory
        if(!File::exists(base_path(config('laravel-package-generator.packages-folder')) . "/" .$packageVendor . "/" . $packageNamePath)){
            File::makeDirectory(base_path(config('laravel-package-generator.packages-folder')) . "/" .$packageVendor . "/" . $packageNamePath);
        }

        $packagePath = base_path(config('laravel-package-generator.packages-folder')) . "/" .$packageVendorPath . "/" . $packageNamePath;

        //Build a package inside vendor directory
        $packageConfig !== 'yes' ? null : $this->handelFile('config', $packagePath. "/config", 'folder');
        $packageMigration !== 'yes' ? null : $this->handelFile('database', $packagePath. "/database", 'folder');
        $packageView !== 'yes' ? null : $this->handelFile('resources', $packagePath. "/resources", 'folder');
        $packageRoute !== 'yes' ? null : $this->handelFile('routes', $packagePath. "/routes", 'folder');
        $this->handelFile('src', $packagePath. "/src", 'folder');
        $this->handelFile('tests', $packagePath. "/tests", 'folder');
        $this->handelFile('/../stubs/github', $packagePath. "/.github", 'folder');
        $this->handelFile('LICENSE.md', $packagePath. "/LICENSE.md");
        $this->handelFile('CHANGELOG.md', $packagePath. "/CHANGELOG.md");
        $this->handelFile('SECURITY.md', $packagePath. "/SECURITY.md");

        $vendorNamespace = Str::of($packageVendorNamespaceString)->camel()->ucfirst()->toString();
        $packageNamespace = Str::of($packageNamePath)->camel()->ucfirst()->toString();
        $packageProviderName = Str::of($packageNamePath)->camel()->ucfirst()->toString() . "ServiceProvider";
        $packagePluginProviderName = Str::of($packageNamePath)->camel()->ucfirst()->toString() . "Plugin";
        $fullNameSpace = $vendorNamespace . "\\". $packageNamespace;

        //Build Stubs Files
        $commandClassName = $packageString->studly()->append('Install')->toString();
        $this->generateStubs(
            $this->stubPath . 'command.stub',
            $packagePath . '/src/Console/'. $commandClassName . ".php",
            [
                "namespace" => $fullNameSpace,
                "name" => $commandClassName,
                "title" => Str::of($packageNamePath)->replace('-', ' ')->ucfirst()->toString(),
                "command" => $packageNamePath,
                "packageName" => Str::of($packageNamePath)->camel()->toString(),
                "provider" => $packageProviderName
            ],
            [
                $packagePath . '/src/Console/'
            ]
        );

        //Build Provider Register Methods
        $register = collect([]);
        $register->push('//Register generate command');
        $register->push('        $this->commands([');
        $register->push('           '."\\".$fullNameSpace."\\Console\\".$commandClassName.'::class,');
        $register->push('        ]);');
        $register->push(" ");
        if($packageConfig === 'yes'){
            $register->push('        //Register Config file');
            $register->push('        $this->mergeConfigFrom(__DIR__.\'/../config/'.$packageNamePath.'.php\', \''.$packageNamePath.'\');');
            $register->push(" ");
            $register->push('        //Publish Config');
            $register->push('        $this->publishes([');
            $register->push('           __DIR__.\'/../config/'.$packageNamePath.'.php\' => config_path(\''.$packageNamePath.'.php\'),');
            $register->push('        ], \''.$packageNamePath.'-config\');');
            $register->push(" ");

            //Generate Config file
            $this->generateStubs(
                $this->stubPath . 'config.stub',
                $packagePath . '/config/'.$packageNamePath.'.php',
                [
                ],
            );
        }
        if($packageMigration === 'yes'){
            $register->push('        //Register Migrations');
            $register->push('        $this->loadMigrationsFrom(__DIR__.\'/../database/migrations\');');
            $register->push(" ");
            $register->push('        //Publish Migrations');
            $register->push('        $this->publishes([');
            $register->push('           __DIR__.\'/../database/migrations\' => database_path(\'migrations\'),');
            $register->push('        ], \''.$packageNamePath.'-migrations\');');
        }
        if($packageView === 'yes'){
            $register->push('        //Register views');
            $register->push('        $this->loadViewsFrom(__DIR__.\'/../resources/views\', \''.$packageNamePath.'\');');
            $register->push(" ");
            $register->push('        //Publish Views');
            $register->push('        $this->publishes([');
            $register->push('           __DIR__.\'/../resources/views\' => resource_path(\'views/vendor/'.$packageNamePath.'\'),');
            $register->push('        ], \''.$packageNamePath.'-views\');');
            $register->push(" ");
            $register->push('        //Register Langs');
            $register->push('        $this->loadTranslationsFrom(__DIR__.\'/../resources/lang\', \''.$packageNamePath.'\');');
            $register->push(" ");
            $register->push('        //Publish Lang');
            $register->push('        $this->publishes([');
            $register->push('           __DIR__.\'/../resources/lang\' => base_path(\'lang/vendor/'.$packageNamePath.'\'),');
            $register->push('        ], \''.$packageNamePath.'-lang\');');
            $register->push(" ");
        }
        if($packageRoute === 'yes') {
            $register->push('        //Register Routes');
            $register->push('        $this->loadRoutesFrom(__DIR__.\'/../routes/web.php\');');
            $register->push(" ");
        }

        //Generate Provider File
        $this->generateStubs(
            $this->stubPath . 'provider.stub',
            $packagePath . '/src/'. $packageProviderName . ".php",
            [
                "namespace" => $fullNameSpace,
                "name" => $packageProviderName,
                "register" => $register->implode("\n")
            ],
            [
                $packagePath . '/src'
            ]
        );

        //Generate Plugin Provider
        $this->generateStubs(
            $this->stubPath . 'plugin.stub',
            $packagePath . '/src/'. $packagePluginProviderName . ".php",
            [
                "namespace" => $fullNameSpace,
                "name" => $packagePluginProviderName,
                "alias" => $packageString
            ],
            [
                $packagePath . '/src'
            ]
        );

        $filamentTestRequired = collect([]);
        $filamentTestRequired->push('"require-dev": {');
        $filamentTestRequired->push('       "laravel/pint": "^1.18",');
        $filamentTestRequired->push('       "livewire/livewire": "^2.10|^3.0",');
        $filamentTestRequired->push('       "nunomaduro/larastan": "^2.9",');
        $filamentTestRequired->push('       "orchestra/testbench": "^9.5",');
        $filamentTestRequired->push('       "pestphp/pest": "^2.36",');
        $filamentTestRequired->push('       "pestphp/pest-plugin-laravel": "^2.4",');
        $filamentTestRequired->push('       "pestphp/pest-plugin-livewire": "^2.1",');
        $filamentTestRequired->push('       "phpstan/extension-installer": "^1.4",');
        $filamentTestRequired->push('       "phpstan/phpstan-deprecation-rules": "^1.2",');
        $filamentTestRequired->push('       "phpstan/phpstan-phpunit": "^1.4"');
        $filamentTestRequired->push('    },');

        //Generate Composer.json file
        $this->generateStubs(
            $this->stubPath . 'composer.stub',
            $packagePath . '/composer.json',
            [
                "vendor" => $packageVendorPath,
                "package" => $packageNamePath,
                "description" => $packageDescription,
                "namespace" => $vendorNamespace ."\\\\". $packageNamespace,
                "provider" => $packageProviderName,
                "author" => $packageAuthor,
                "email" => $packageAuthorEmail,
                "filament" => $filamentTestRequired->implode("\n")
            ],
            [
                $packagePath
            ]
        );


        $filamentPluginMD = collect([]);
        $filamentPluginMD->push('finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`');
        $filamentPluginMD->push('');
        $filamentPluginMD->push('```php');
        $filamentPluginMD->push('->plugin(\\'.$vendorNamespace.'\\'.$packageNamespace.'\\'.$packagePluginProviderName.'::make())');
        $filamentPluginMD->push('```');
        $filamentPluginMD->push('');


        //Generate Readme.md file
        $this->generateStubs(
            $this->stubPath . 'readme.stub',
            $packagePath . '/README.md',
            [
                "name" => Str::of($packageNamePath)->replace('-', ' ')->ucfirst()->toString(),
                "vendor" => $packageVendorPath,
                "command" => $packageNamePath,
                "package" => $packageNamePath,
                "description" => $packageDescription,
                "author" => $packageAuthor,
                "email" => $packageAuthorEmail,
                "vendorName" => $vendorNamespace,
                "plugin" => $filamentPluginMD->implode("\n")
            ],
            [
                $packagePath
            ]
        );

        //Generate test phpunit.xml
        $this->generateStubs(
            $this->stubPath . 'phpunit.stub',
            $packagePath . '/phpunit.xml',
            [
                "vendor" => $packageVendorPath,
                "package" => $packageNamePath,
            ],
            [
                $packagePath
            ]
        );

        //Generate plugin.md
        $this->generateStubs(
            $this->stubPath . 'plugin-md.stub',
            $packagePath . '/plugin.md',
            [
                "name" => Str::of($packageNamePath)->replace('-', ' ')->ucfirst()->toString(),
                "author" => $packageVendorPath,
                "alias" => $packageString,
                "vendor" => $packageVendorPath,
                "package" => $packageNamePath,
                "description" => $packageDescription,
                "date" => Carbon::today()->toDateString()
            ],
            [
                $packagePath
            ]
        );

        //Generate plugin.md
        $this->generateStubs(
            $this->stubPath . 'module.stub',
            $packagePath . '/module.json',
            [
                "name" => Str::of($packageNamePath)->replace('-', ' ')->ucfirst()->toString(),
                "author" => $packageVendorPath,
                "alias" => $packageString,
                "vendor" => $packageVendorPath,
                "package" => $packageNamePath,
                "description" => $packageDescription,
                "date" => Carbon::today()->toDateString(),
                "space" => $packageNamespace,
                "namespace" => $vendorNamespace ."\\\\". $packageNamespace,
                "provider" => $packageProviderName,
            ],
            [
                $packagePath
            ]
        );

        //Generate config.yaml
        $this->generateStubs(
            $this->stubPath . '/ISSUE_TEMPLATE/config.stub',
            $packagePath . '/.github/ISSUE_TEMPLATE/config.yaml',
            [
                "vendor" => $packageVendorPath,
                "package" => $packageNamePath,
            ],
            [
                $packagePath
            ]
        );

        //Generate UserFactory.php
        $this->generateStubs(
            $this->stubPath . '/tests/database/factories/UserFactory.stub',
            $packagePath . '/tests/database/factories/UserFactory.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
            ],
            [
                $packagePath
            ]
        );

        //Generate User.php
        $this->generateStubs(
            $this->stubPath . '/tests/src/Models/User.stub',
            $packagePath . '/tests/src/Models/User.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
            ],
            [
                $packagePath
            ]
        );

        //Generate AdminPanelProvider.php
        $this->generateStubs(
            $this->stubPath . '/tests/src/AdminPanelProvider.stub',
            $packagePath . '/tests/src/AdminPanelProvider.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
                "plugin" => $packagePluginProviderName
            ],
            [
                $packagePath
            ]
        );

        //Generate PluginTest.php
        $this->generateStubs(
            $this->stubPath . '/tests/src/PluginTest.stub',
            $packagePath . '/tests/src/PluginTest.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
                "alias" => $packageString,
                "plugin" => $packagePluginProviderName
            ],
            [
                $packagePath
            ]
        );

        //Generate TestCase.php
        $this->generateStubs(
            $this->stubPath . '/tests/src/TestCase.stub',
            $packagePath . '/tests/src/TestCase.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
                "provider" => $packageProviderName,
            ],
            [
                $packagePath
            ]
        );

        //Generate PluginTest.php
        $this->generateStubs(
            $this->stubPath . '/tests/Pest.stub',
            $packagePath . '/tests/Pest.php',
            [
                "namespace" => $vendorNamespace ."\\". $packageNamespace,
                "provider" => $packageProviderName,
            ],
            [
                $packagePath
            ]
        );

        $this->info('Package boilerplate generated successfully');
    }
}
