<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource\Contracts;

trait GenerateInfoList
{
    protected string $infoListEntriesNamespace;
    protected string $infoListNamespace;


    protected function generateInfoList(): void
    {
        $this->infoListNamespace = $this->resourceNamespace . '\InfoList';
        $this->infoListEntriesNamespace = $this->infoListNamespace . '\Entries';

        $this->generateInfoListEntriesAbstract();
        $this->generateInfoListClass();
    }

    /**
     * @return void
     */
    protected function generateInfoListEntriesAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/InfoList/Entries/Entry.stub',
            to: $this->resourcePath . '/InfoList/Entries/Entry.php',
            replacements: [
                'namespace' => $this->infoListEntriesNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateInfoListClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/InfoList/InfoList.stub',
            to: $this->resourcePath . '/InfoList/'.$this->resourceTitle.'InfoList.php',
            replacements: [
                'namespace' => $this->infoListNamespace,
                'name' => $this->resourceTitle . 'InfoList',
            ]
        );
    }
}
