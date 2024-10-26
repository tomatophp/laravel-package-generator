<?php

namespace TomatoPHP\LaravelPackageGenerator\Services\Resource\Contracts;

trait GenerateTable
{
    protected string $tableHeaderActionsNamespace;
    protected string $tableActionsNamespace;
    protected string $tableFiltersNamespace;
    protected string $tableBulkActionsNamespace;
    protected string $tableColumnsNamespace;

    protected string $tableNamespace;


    protected function generateTable(): void
    {
        $this->tableNamespace = $this->resourceNamespace . '\Table';
        $this->tableHeaderActionsNamespace = $this->tableNamespace . '\HeaderActions';
        $this->tableActionsNamespace = $this->tableNamespace . '\Actions';
        $this->tableFiltersNamespace = $this->tableNamespace . '\Filters';
        $this->tableBulkActionsNamespace = $this->tableNamespace . '\BulkActions';
        $this->tableColumnsNamespace = $this->tableNamespace . '\Columns';

        $this->generateTableHeaderActions();
        $this->generateTableActions();
        $this->generateTableFilters();
        $this->generateTableBulkActions();
        $this->generateTableColumns();
        $this->generateTableClass();
    }

    /**
     * @return void
     */
    protected function generateTableHeaderActions(): void
    {
        $this->generateTableHeaderActionsAbstract();
        $this->generateTableHeaderActionsClass();
    }

    /**
     * @return void
     */
    protected function generateTableHeaderActionsAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/HeaderActions/Action.stub',
            to: $this->resourcePath . '/Table/HeaderActions/Action.php',
            replacements: [
                'namespace' => $this->tableHeaderActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableHeaderActionsClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/HeaderActions.stub',
            to: $this->resourcePath . '/Table/'.$this->resourceTitle.'HeaderActions.php',
            replacements: [
                'namespace' => $this->tableNamespace,
                'name' => $this->resourceTitle . 'HeaderActions',
            ]
        );
    }


    /**
     * @return void
     */
    protected function generateTableActions(): void
    {
        $this->generateTableActionsAbstract();
        $this->generateTableActionsView();
        $this->generateTableActionsEdit();
        $this->generateTableActionsDelete();
        $this->generateTableActionsClass();
    }

    /**
     * @return void
     */
    protected function generateTableActionsAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Actions/Action.stub',
            to: $this->resourcePath . '/Table/Actions/Action.php',
            replacements: [
                'namespace' => $this->tableActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableActionsView(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Actions/ViewAction.stub',
            to: $this->resourcePath . '/Table/Actions/ViewAction.php',
            replacements: [
                'namespace' => $this->tableActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableActionsEdit(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Actions/EditAction.stub',
            to: $this->resourcePath . '/Table/Actions/EditAction.php',
            replacements: [
                'namespace' => $this->tableActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableActionsDelete(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Actions/DeleteAction.stub',
            to: $this->resourcePath . '/Table/Actions/DeleteAction.php',
            replacements: [
                'namespace' => $this->tableActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableActionsClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Actions.stub',
            to: $this->resourcePath . '/Table/'.$this->resourceTitle.'Actions.php',
            replacements: [
                'namespace' => $this->tableNamespace,
                'name' => $this->resourceTitle . 'Actions',
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableFilters(): void
    {
        $this->generateTableFiltersAbstract();
        $this->generateTableFiltersClass();
    }

    /**
     * @return void
     */
    protected function generateTableFiltersAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Filters/Filter.stub',
            to: $this->resourcePath . '/Table/Filters/Filter.php',
            replacements: [
                'namespace' => $this->tableFiltersNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableFiltersClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Filters.stub',
            to: $this->resourcePath . '/Table/'.$this->resourceTitle.'Filters.php',
            replacements: [
                'namespace' => $this->tableNamespace,
                'name' => $this->resourceTitle . 'Filters',
            ]
        );
    }


    /**
     * @return void
     */
    protected function generateTableBulkActions(): void
    {
        $this->generateTableBulkActionsAbstract();
        $this->generateTableBulkActionsDelete();
        $this->generateTableBulkActionsClass();
    }

    /**
     * @return void
     */
    protected function generateTableBulkActionsAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/BulkActions/Action.stub',
            to: $this->resourcePath . '/Table/BulkActions/Action.php',
            replacements: [
                'namespace' => $this->tableBulkActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableBulkActionsDelete(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/BulkActions/DeleteAction.stub',
            to: $this->resourcePath . '/Table/BulkActions/DeleteAction.php',
            replacements: [
                'namespace' => $this->tableBulkActionsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableBulkActionsClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/BulkActions.stub',
            to: $this->resourcePath . '/Table/'.$this->resourceTitle.'BulkActions.php',
            replacements: [
                'namespace' => $this->tableNamespace,
                'name' => $this->resourceTitle . 'BulkActions',
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableColumns(): void
    {
        $this->generateTableColumnsAbstract();
        $this->generateTableIdColumn();
        $this->generateTableCreatedAtColumn();
        $this->generateTableUpdatedAtColumn();
    }

    /**
     * @return void
     */
    protected function generateTableColumnsAbstract(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Columns/Column.stub',
            to: $this->resourcePath . '/Table/Columns/Column.php',
            replacements: [
                'namespace' => $this->tableColumnsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableIdColumn(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Columns/Id.stub',
            to: $this->resourcePath . '/Table/Columns/Id.php',
            replacements: [
                'namespace' => $this->tableColumnsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableCreatedAtColumn(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Columns/CreatedAt.stub',
            to: $this->resourcePath . '/Table/Columns/CreatedAt.php',
            replacements: [
                'namespace' => $this->tableColumnsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableUpdatedAtColumn(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Columns/UpdatedAt.stub',
            to: $this->resourcePath . '/Table/Columns/UpdatedAt.php',
            replacements: [
                'namespace' => $this->tableColumnsNamespace,
            ]
        );
    }

    /**
     * @return void
     */
    protected function generateTableClass(): void
    {
        $this->generateStubs(
            from: __DIR__ . '/../stubs/Table/Table.stub',
            to: $this->resourcePath . '/Table/'.$this->resourceTitle.'Table.php',
            replacements: [
                'namespace' => $this->tableNamespace,
                'name' => $this->resourceTitle . 'Table',
                'bulkActions' => $this->resourceTitle . 'BulkActions',
                'actions' => $this->resourceTitle . 'Actions',
                'filters' => $this->resourceTitle . 'Filters',
                'headerActions' => $this->resourceTitle . 'HeaderActions',
            ]
        );
    }
}
