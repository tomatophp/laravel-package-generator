<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource\Contracts;

trait GenerateForm
{
    protected string $formComponentsNamespace;
    protected string $formNamespace;

    /**
     * @return void
     */
    protected function generateForm(): void
    {
        $this->formNamespace = $this->resourceNamespace . '\Form';
        $this->formComponentsNamespace = $this->formNamespace . '\Components';

        $this->generateFormComponentsAbstract();
        $this->generateFormClass();
    }

    /**
     * @return void
     */
    protected function generateFormComponentsAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Form/Components/Component.stub',
            to: $this->resourcePath . '/Form/Components/Component.php',
            replacements: [
                'namespace' => $this->formComponentsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateFormClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Form/Form.stub',
            to: $this->resourcePath . '/Form/'.$this->resourceTitle.'Form.php',
            replacements: [
                'namespace' => $this->formNamespace,
                'name' => $this->resourceTitle . 'Form',
            ]
        );
    }
}
