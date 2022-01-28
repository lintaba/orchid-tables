<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Screen;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

abstract class TableAdvanced extends Table
{
    protected $template = 'platform::layouts.tableAdvanced';

    /**
     * @param Repository $repository
     *
     * @return null|Factory
     */
    public function build(Repository $repository)
    {
        $this->query = $repository;

        if (!$this->isSee()) {
            return null;
        }

        $columns = collect($this->columns())->filter(static function (TD $column) {
            return $column->isSee();
        });

        $total = collect($this->total())->filter(static function (TD $column) {
            return $column->isSee();
        });

        $rows = $repository->getContent($this->target);
        $rows = is_array($rows) ? collect($rows) : $rows;

        return view($this->template, [
            'repository'   => $repository,
            'rows'         => $rows,
            'columns'      => $columns,
            'total'        => $total,
            'iconNotFound' => $this->iconNotFound(),
            'textNotFound' => $this->textNotFound(),
            'subNotFound'  => $this->subNotFound(),
            'striped'      => $this->striped(),
            'compact'      => $this->compact(),
            'bordered'     => $this->bordered(),
            'hoverable'    => $this->hoverable(),
            'slug'         => $this->getSlug(),
            'onEachSide'   => $this->onEachSide(),
            'title'        => $this->title,
            'rowClass'     => [$this, 'rowClass'],
            'rowLink'      => [$this, 'rowLink'],
        ]);
    }

    /**
     * @param Repository|Model $row
     *
     * @return null|string
     */
    public function rowClass($row)
    {
        return null;
    }

    /**
     * @param Repository|Model $row
     *
     * @return string|null
     */
    public function rowLink($row)
    {
        return null;
    }

    /**
     * Enable a hover state on table rows.
     *
     * @return bool
     */
    protected function hoverable(): bool
    {
        return true;
    }
}
