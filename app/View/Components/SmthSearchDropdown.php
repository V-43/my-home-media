<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SmthSearchDropdown extends Component
{
    public $smth,  //тип поиска (страна, режиссер, сценарист)
        $label, 
        $added, //список добавленных
        $found; //список найденных по введенной строке

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($smth, $label, $added, $found)
    {
        $this->smth = $smth;
        $this->label = $label;
        $this->added = $added;
        $this->found = $found;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.smth-search-dropdown');
    }
}
