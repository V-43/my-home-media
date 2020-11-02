<?php

namespace App\Http\Livewire;

use App\Models\Genre;
use App\Models\Video;
use App\Models\Person;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateFilm extends Component
{
    use WithFileUploads;

    public $filmData;
    public $title, $titleAlt, $year, $description;
    public $genres, $allgenres, $orderedGenres;
    
    public $poster, $video;
    public $submitted = false;
    
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
        $this->add('country', $country, 'countries');
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
    
    public function submit()
    {
        // if ($this->video) {
            $this->store();
        // }
        $this->submitted = true;
        // return redirect()->to('/');
    }

    public function store()
    {
        $video = new Video();
        $video->title = $this->title;
        $video->title_alt = $this->titleAlt;
        $video->year = $this->year;
        $video->description = $this->description;
        $video->id_kinopoisk = $this->filmData['id_kinopoisk'];
        $video->save();

        $genres = Genre::whereIn('name', $this->orderedGenres)->get();
        foreach ($this->orderedGenres as $ordGenre) {
            foreach ($genres as $key => $genre) {
                if ($genre->name === $ordGenre) {
                    $genres[] = $genre;
                    unset($genres[$key]);
                    break;
                }
            }
        }
        $video->genres()->saveMany($genres);

        foreach ($this->countries as $country) {
            $countries[] = Country::firstOrCreate(['name' => $country]);
        }
        $video->countries()->saveMany($countries);

        foreach ($this->directors as $director) {
            $people[$director] = 1;
        }
        foreach ($this->screenwriters as $screenwriter) {
            $people[$screenwriter] = isset($people[$screenwriter]) ? $people[$screenwriter] + 2 : 2;
        }
        foreach ($people as $person => $role) {
            $id = Person::firstOrCreate(['name' => $person])->id;
            $people[$id] = compact('role');
            unset($people[$person]);
        }
        $video->people()->attach($people);

        if ($this->poster) {
            $this->poster->storeAs("public/films/$video->id/", 'poster.jpg');
        } else {
            $poster = file_get_contents('https:'.$this->filmData['poster']);
            Storage::put("public/films/$video->id/poster.jpg", $poster);
        }

        return redirect()->route('movies.show', ['id' => $video->id]);
    }

    public function mount()
    {
        $this->fill([ 
            'title' => $this->filmData['title'],
            'titleAlt' => $this->filmData['title_alternative'],
            'year' => $this->filmData['year'],
            'countries' => $this->filmData['countries'],
            'genres' => $this->filmData['genres'], //для wire:model="genres" (тег select)
            'orderedGenres' => $this->filmData['genres'], //для запоминания порядка выбора жанров
            'allgenres' => Genre::all()->pluck('name'),
            'directors' => $this->filmData['directors'],
            'screenwriters' => $this->filmData['screenwriters'],
            'description' => str_replace('', '—', $this->filmData['description']), //иначе тире отображается некорректно
        ]);
    }

    public function updatedVideo()
    {
        if ($this->submitted) {
            // $this->video->storeAs('public/films/12345/', 'film.'.$this->video->getClientOriginalExtension());
            $this->store();
        }
    }

    public function render()
    {
        $this->foundCountries = [];
        $this->foundDirectors = [];
        $this->foundScreenwriters = [];

        if (mb_strlen($this->country) >= 2) {
            $this->foundCountries = Country::where('name', 'like', "$this->country%")
                ->whereNotIn('name', $this->countries)
                ->take(5)
                ->get()
                ->pluck('name');
        }

        if (mb_strlen($this->director) >= 2) {
            $this->foundDirectors = Person::where('name', 'like', "$this->director%")
                ->whereNotIn('name', $this->directors)
                ->take(5)
                ->get()
                ->pluck('name');
        }

        if (mb_strlen($this->screenwriter) >= 2) {
            $this->foundScreenwriters = Person::where('name', 'like', "$this->screenwriter%")
                ->whereNotIn('name', $this->screenwriters)
                ->take(5)
                ->get()
                ->pluck('name');
        }

        return view('livewire.create-film');
    }
    
    private function add(string $field, string $value, string $plural = null)
    {
        $plural = $plural ?? $field.'s';
        $this->$plural[] = $value;
        $this->$field = '';
        $this->{'found'.ucfirst($plural)} = [];
    }
}
