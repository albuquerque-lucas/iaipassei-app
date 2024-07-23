<?php

namespace App\View\Components\Sections;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class AssociatedExaminations extends Component
{
    public LengthAwarePaginator $examinations;

    public function __construct(LengthAwarePaginator $examinations)
    {
        $this->examinations = $examinations;
    }

    public function render(): View|Closure|string
    {
        return view('components.sections.associated-examinations', [
            'examinations' => $this->examinations,
        ]);
    }
}
