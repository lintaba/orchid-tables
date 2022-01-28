<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Mixins;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class LayoutMixin
{
    public static function html(): callable
    {
        return function (string $content) {
            return new class ($content) extends Layout {
                public $content;

                public function __construct($content)
                {
                    $this->content = $content;
                }

                public function build(Repository $repository)
                {
                    return (string)value($this->content);
                }
            };
        };
    }
}
