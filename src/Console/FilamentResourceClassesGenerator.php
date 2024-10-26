<?php

namespace TomatoPHP\LaravelPackageGenerator\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\LaravelPackageGenerator\Services\Resource\FilamentResourceGenerator;
use function Laravel\Prompts\error;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class FilamentResourceClassesGenerator extends Command
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
    protected $signature = 'package:filament-resource {vendor} {package} {resource} {namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Filament Resource Classes';

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
        $getPackageVendors = [];
        if(File::exists(base_path('packages'))){
            $getPackageVendors = File::directories(base_path('packages'));
        }

        $packageVendor = $this->argument('vendor') ?? suggest(
            label:"Enter your package vendor name?",
            options: collect($getPackageVendors)->map(function($vendor){
                return Str::afterLast($vendor, '/');
            })->toArray(),
            required: true,
            hint: "Like: tomatophp",
        );

        $getPackages = [];
        if(File::exists(base_path('packages/' . $packageVendor))){
            $getPackages = File::directories(base_path('packages/' . $packageVendor));
        }


        $packageName = $this->argument('package') ?? suggest(
            label: "Enter your package name",
            options: collect($getPackages)->map(function($vendor){
                return Str::afterLast($vendor, '/');
            })->toArray(),
            required: true,
            hint: "Like: filament-users",
        );

        $packagePathExists = File::exists(base_path("packages/$packageVendor/$packageName/src/Filament/Resources"));
        if(!$packagePathExists){
            error("Package path does not exist. Please create the package first.");
            return;
        }

        $scanPackageResources = File::directories(base_path("packages/$packageVendor/$packageName/src/Filament/Resources"));

        $resourceName = $this->argument('resource') ??  suggest(
            label: "Select the resource name",
            options: collect($scanPackageResources)->map(function($vendor){
                return Str::afterLast($vendor, '/');
            })->toArray(),
            required: true,
        );

        $namespace = $this->argument('namespace') ??  text(
            label: "Enter your vendor package namespace",
            required: true,
            hint: "Like: TomatoPHP\\FilamentUsers",
        );

        new FilamentResourceGenerator(
            $packageVendor,
            $packageName,
            $resourceName,
            $namespace,
        );

    }
}
