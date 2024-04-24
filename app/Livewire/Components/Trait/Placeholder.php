<?php

namespace App\Livewire\Components\Trait;

trait Placeholder
{
    public function placeholder(): string
    {
        return <<<'HTML'
        <div>
            <svg>...</svg>
        </div>
        HTML;
    }
}
