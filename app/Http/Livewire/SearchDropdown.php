<?php

namespace App\Http\Livewire;

use App\KinopoiskApi;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [
            'kinopoisk' => collect([]),
            'hasInDb' => collect([])
        ];

        if (mb_strlen($this->search) >= 2) {
            $searchResults = KinopoiskApi::findFilmsByKeyword($this->search);
        }
        
        return view('livewire.search-dropdown', [
            'searchResults' => $searchResults
        ]);
    }

}
