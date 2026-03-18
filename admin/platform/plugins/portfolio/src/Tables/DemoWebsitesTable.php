<?php

namespace Botble\Portfolio\Tables;

use Botble\Portfolio\Models\DemoWebsite;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Illuminate\Database\Eloquent\Builder;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\StatusColumn;



class DemoWebsitesTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(DemoWebsite::class)
            ->addActions([
                EditAction::make()->route('portfolio.demo-websites.edit'),
                DeleteAction::make()->route('portfolio.demo-websites.destroy'),
            ]);
    }

    public function query(): Builder
    {
        $query = $this->model;
        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make('img_feature'),
            NameColumn::make(),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('portfolio.demo-websites.create'), 'portfolio.demo-websites.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('portfolio.demo-websites.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
