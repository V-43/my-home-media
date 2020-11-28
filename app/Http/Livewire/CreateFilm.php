<?php

namespace App\Http\Livewire;

use App\Models\Genre;
use App\Models\Country;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Services\FindPeopleService;
use App\Services\CreateVideoService;
use Illuminate\Support\Facades\Storage;

//@todo: видеофайлы должны грузиться "чанками", а не целиком
class CreateFilm extends Component
{
    use WithFileUploads;

    public $filmData;
    public $title, $titleAlt, $year, $description;
    public $genres, $allgenres, $orderedGenres, $posterUrl; //posterUrl - url предоставленный API
    
    public $poster, $video; //предоставленные юзером файлы
    
    public $country, $foundCountries, $countries;
    public $director, $foundDirectors, $directors;
    public $screenwriter, $foundScreenwriters, $screenwriters;

    public function setGenre(string $genre, bool $selected, bool $ctrl)
    {
        if ($ctrl) {
            if ($selected) {
                $this->orderedGenres[] = $genre;
            } else {
                $this->orderedGenres = array_flip($this->orderedGenres);
                unset($this->orderedGenres[$genre]);
                $this->orderedGenres = array_flip($this->orderedGenres);
            }
        } else {
            $this->orderedGenres = [$genre];
        }
        $this->genres = $this->orderedGenres;
    }

    public function addCountry(string $country)
    {
        $this->add('country', $country);
    }
    public function addDirector(string $director)
    {
        $this->add('director', $director);
    }
    public function addScreenwriter(string $screenwriter)
    {
        $this->add('screenwriter', $screenwriter);
    }

    public function removeCountry(int $idx)
    {
        unset($this->countries[$idx]);
    }
    public function removeDirector(int $idx)
    {
        unset($this->directors[$idx]);
    }
    public function removeScreenwriter(int $idx)
    {
        unset($this->screenwriters[$idx]);
    }
    
    public function store() //@todo: валидация
    {
        $videoId = (new CreateVideoService)->make([
            'video' => [
                'title' => $this->title,
                'title_alt' => $this->titleAlt,
                'year' => $this->year,
                'description' => $this->description,
                'id_kinopoisk' => $this->filmData['id_kinopoisk'],
            ],
            'people' => [
                'directors' => $this->directors,
                'screenwriters' => $this->screenwriters,
            ],
            'genres' => $this->orderedGenres,
            'countries' => $this->countries,
            'poster' => $this->poster ?? $this->posterUrl,
            'videofile' => $this->video,
        ]);

        return redirect()->route('movies.show', ['video' => $videoId]);
    }

    public function mount()
    {
        $this->fill($this->filmData);
        $this->fill([
            'orderedGenres' => $this->genres, //для запоминания порядка выбора жанров
            'allgenres' => Genre::all()->pluck('name'),
        ]);
    }

    public function render()
    {
        $this->foundCountries = collect([]);
        $this->foundDirectors = collect([]);
        $this->foundScreenwriters = collect([]);

        if (mb_strlen($this->country) >= 2) {
            $this->foundCountries = Country::where('name', 'like', "$this->country%")
                ->whereNotIn('name', $this->countries)
                ->take(5)
                ->get()
                ->pluck('name');
        }

        if (mb_strlen($this->director) >= 2) { 
            $this->foundDirectors = FindPeopleService::get($this->director, $this->directors);
        }

        if (mb_strlen($this->screenwriter) >= 2) {
            $this->foundScreenwriters = FindPeopleService::get($this->screenwriter, $this->screenwriters);
        }

        return view('livewire.create-film');
    }
    
    private function add(string $field, string $value)
    {
        $plural = Str::plural($field);
        $this->$plural[] = $value;
        $this->$field = '';
        $this->{'found'.ucfirst($plural)} = [];
    }
}
