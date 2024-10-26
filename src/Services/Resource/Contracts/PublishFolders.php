<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource\Contracts;

trait PublishFolders
{

    /**
     * @return void
     */
    protected function publishFolders(): void
    {
        $this->copyFile(
            from: __DIR__ . '/../stubs/publish',
            to: $this->resourcePath,
            type: 'folder'
        );

        \Laravel\Prompts\info("Published Filament Resource Folder Classes for {$this->resourceName}");
    }
}
