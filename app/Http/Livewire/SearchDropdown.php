<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];

        if (mb_strlen($this->search) >= 2) {
            $searchResults = $this->getSearchResults();
        }
        
        return view('livewire.search-dropdown', [
            'searchResults' => $searchResults
        ]);
    }

    private function getSearchResults() {
        $searchResults = Http::withHeaders([ 
            'Accept' => 'application/json',
            'Referer' => 'https://www.kinopoisk.ru/',
            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:81.0) Gecko/20100101 Firefox/81.0',
            'X-Requested-With' => 'XMLHttpRequest'
        ])->get('https://www.kinopoisk.ru/api/suggest/v2/?query='.$this->search)
        ->json()['suggest']['top'];
        
        if (!isset($searchResults['topResult'])) {
            return [];
        }

        $topResult = $searchResults['topResult']['global'];
        if ($topResult['__typename'] === 'Film' && isset($topResult['productionYear']) && $topResult['productionYear'] <= date("Y")) {
            $results[] = [
                'kinopoiskId' => $topResult['id'],
                'russian' => $topResult['title']['russian'],
                'original' => $topResult['title']['original'],
                'productionYear' => $topResult['productionYear'],
                'rating' => round($topResult['rating']['kinopoisk']['value'], 1),
                'poster' => $topResult['poster'] ? 
                    $topResult['poster']['avatarsUrl'] . '/300x450' :
                    '//via.placeholder.com/100x150',
            ];
        }

        foreach ($searchResults['movies'] as $movie) {
            $movie = $movie['movie'];
            if ($movie['__typename'] === 'Film' && isset($movie['productionYear']) && $movie['productionYear'] <= date("Y")) {
                $results[] = [
                    'kinopoiskId' => $movie['id'],
                    'russian' => $movie['title']['russian'],
                    'original' => $movie['title']['original'],
                    'productionYear' => $movie['productionYear'],
                    'rating' => round($movie['rating']['kinopoisk']['value'], 1),
                    'poster' => $movie['poster'] ? 
                        $movie['poster']['avatarsUrl'] . '/300x450' :
                        '//via.placeholder.com/100x150',
                ];
            }
        }

        return $results;
    }
}
