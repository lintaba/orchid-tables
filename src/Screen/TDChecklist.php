<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Screen;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

class TDChecklist extends TD
{
    /** @var Closure[] */
    private array $renderCallbacks = [];

    /**
     * Builds a column heading.
     *
     * @return Factory|Application|\Illuminate\Contracts\View\View
     */
    public function buildTh()
    {
        return view('platform::layouts.checklistTh', [
            'width'   => $this->width ?? 100,
            'align'   => $this->align,
            'column'  => $this->column,
            'id'      => $this->id(),
            'title'   => $this->title,
            'slug'    => $this->sluggable(),
            'popover' => $this->popover,
        ]);
    }

    public function checkboxSet($key, $value): self
    {
        $this->renderCallbacks[] = [$key, $value];

        return $this;
    }

    /**
     * Builds content for the column.
     *
     * @param Repository|Model $repository
     *
     * @return Factory|Application|\Illuminate\Contracts\View\View
     */
    public function buildTd($repository, ?object $loop = null)
    {
        $value    = $repository->getKey();
        $checkbox = CheckBox::make($this->sluggable() . '[]');

        $checkbox->value($value)->class('form-check-input cb-check cb-check-' . $this->id())->checked(in_array(
            $value,
            old($this->sluggable(), []),
            false
        ));

        foreach ($this->renderCallbacks as [$key, $value]) {
            $checkbox->set($key, value($value, $repository));
        }

        return view('platform::layouts.checklistTd', [
            'align'    => $this->align,
            'render'   => $this->render,
            'slug'     => $this->sluggable(),
            'id'       => $this->id(),
            'width'    => $this->width ?? 5,
            'colspan'  => $this->colspan,
            'checkbox' => (new ComponentAttributeBag())->merge($checkbox->getAttributes()),
        ]);
    }

    private $id = null;

    private function id(): string
    {
        if ($this->id === null) {
            $this->id = Str::random(8);
        }

        return $this->id;
    }

    /**
     * @return string
     */
    protected function sluggable(): string
    {
        return Str::slug($this->name) ?: 'checkbox';
    }

    /**
     * Builds item menu for show/hiden column.
     *
     * @return Factory|View|null
     */
    public function buildItemMenu()
    {
        return null;
    }

    public function isExportable(): bool
    {
        return false;
    }
}
