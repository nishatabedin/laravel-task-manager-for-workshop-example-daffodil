<?php

namespace App\View\Components\demo;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DemoComponent2 extends Component
{
    /**
     * Create a new component instance.
     */
    public string $navClassLi4;

    public function __construct(string $navClassLi4)
    {
        $this->navClassLi4 = $navClassLi4;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.demo.demo-component2');
    }

    public function test(){
        return  "test";

    }
}
