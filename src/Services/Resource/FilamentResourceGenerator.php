<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource;

use Illuminate\Support\Collection;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;

class FilamentResourceGenerator
{
    use HandleFiles;
    use HandleStub;
    use Contracts\PublishFolders;
    use Contracts\GenerateActions;
    use Contracts\GenerateForm;
    use Contracts\GenerateTable;
    use Contracts\GenerateInfoList;

    public function __construct(
        protected string $packageVendor,
        protected string $packageName,
        protected string $resourceName,
        protected string $namespace,
        protected ?string $resourcePath=null,
        protected ?string $resourceNamespace=null,
        protected ?string $resourceTitle=null,
    ) {
        \Laravel\Prompts\info("Generating Filament Resource Classes for {$this->resourceName}");

        $this->resourcePath = $this->resourcePath ?? base_path("packages/{$this->packageVendor}/{$this->packageName}/src/Filament/Resources/{$this->resourceName}");
        $this->resourceNamespace = $this->resourceNamespace ?? "{$this->namespace}\\Filament\\Resources\\{$this->resourceName}";
        $this->resourceTitle = str($this->resourceName)->remove('Resource')->toString();

        $this->publishFolders();
        $this->generateActions();
        $this->generateForm();
        $this->generateInfoList();
        $this->generateTable();
    }
}
