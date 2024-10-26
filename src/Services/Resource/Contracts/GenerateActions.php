<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource\Contracts;

trait GenerateActions
{
    protected string $actionsComponentNamespace;
    protected string $actionsNamespace;
    /**
     * @return void
     */
    protected function generateActions(): void
    {
        $this->actionsComponentNamespace = $this->resourceNamespace . '\Actions\Components';
        $this->actionsNamespace = $this->resourceNamespace . '\Actions';

        $this->generateActionsComponents();
        $this->generateBasePageActions();
    }

    /**
     * @return void
     */
    protected function generateActionsComponents(): void
    {
        $this->generateAbstractAction();
        $this->generateCreateAction();
        $this->generateEditAction();
        $this->generateViewAction();
        $this->generateDeleteAction();

    }

    /**
     * @return void
     */
    protected function generateAbstractAction(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/Contracts/CanRegister.stub',
            to: $this->resourcePath . '/Actions/Components/Contracts/CanRegister.php',
            replacements: [
                'namespace' => $this->actionsComponentNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateCreateAction(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/Components/CreateAction.stub',
            to: $this->resourcePath . '/Actions/Components/CreateAction.php',
            replacements: [
                'namespace' => $this->actionsComponentNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateEditAction(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/Components/EditAction.stub',
            to: $this->resourcePath . '/Actions/Components/EditAction.php',
            replacements: [
                'namespace' => $this->actionsComponentNamespace,
                'resourceNamespace' => $this->resourceNamespace,
                'editPage' => 'Edit' . $this->resourceTitle,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateViewAction(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/Components/ViewAction.stub',
            to: $this->resourcePath . '/Actions/Components/ViewAction.php',
            replacements: [
                'namespace' => $this->actionsComponentNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateDeleteAction(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/Components/DeleteAction.stub',
            to: $this->resourcePath . '/Actions/Components/DeleteAction.php',
            replacements: [
                'namespace' => $this->actionsComponentNamespace,
                'name' => 'deleteSelected' . str($this->resourceName)->remove('Resource')->toString(),
            ]
        );
    }

    protected function generateBasePageActions(): void
    {
        $this->generateAbstractPageActions();
        $this->generateCreatePageActions();
        $this->generateEditPageActions();
        $this->generateViewPageActions();
        $this->generateManagePageActions();

    }

    /**
     * @return void
     */
    protected function generateAbstractPageActions(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/BasePageActions.stub',
            to: $this->resourcePath . '/Actions/BasePageActions.php',
            replacements: [
                'namespace' => $this->actionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateCreatePageActions(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/CreatePageActions.stub',
            to: $this->resourcePath . '/Actions/CreatePageActions.php',
            replacements: [
                'namespace' => $this->actionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateEditPageActions(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/EditPageActions.stub',
            to: $this->resourcePath . '/Actions/EditPageActions.php',
            replacements: [
                'namespace' => $this->actionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateViewPageActions(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/ViewPageActions.stub',
            to: $this->resourcePath . '/Actions/ViewPageActions.php',
            replacements: [
                'namespace' => $this->actionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateManagePageActions(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Actions/ManagePageActions.stub',
            to: $this->resourcePath . '/Actions/ManagePageActions.php',
            replacements: [
                'namespace' => $this->actionsNamespace,
            ]
        );
    }
}
