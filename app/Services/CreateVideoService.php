<?php 

namespace App\Services;

use App\Models\Genre;
use App\Models\Video;
use App\Models\Person;
use App\Models\Country;
use Illuminate\Support\Facades\Storage;

class CreateVideoService {
    private $video;
    private $dir = 'public/films';

    public function __construct()
    {
        $this->video = new Video;
    }
    
    public function make(array $filmData)
    {
        /* if (!$filmData['poster'] || $filmData['videofile']) {
            throw new Exception('required poster and video');
        } */

        foreach ($filmData['video'] as $field => $value) {
            $this->video->$field = $value;
        }
        $this->video->save();

        $this->setGenres($filmData['genres']);
        $this->setCountries($filmData['countries']);
        $this->setPeople($filmData['people']);

        $videoId = $this->video->id;

        if (\is_string($filmData['poster'])) {
            $poster = file_get_contents($filmData['poster']);
            Storage::put("$this->dir/$videoId/poster.jpg", $poster);
        } else {
            $filmData['poster']->storeAs("$this->dir/$videoId/", 'poster.jpg');
        }
        
        if (isset($filmData['videofile'])) {
            $filmData['videofile']->storeAs("$this->dir/$videoId/", 'film.'.$filmData['videofile']->getClientOriginalExtension());
        }

        return $videoId;
    }

    private function setGenres(array $genres)
    {
        $dbGenres = Genre::whereIn('name', $genres)->get();
        foreach ($genres as $genre) { 
            foreach ($dbGenres as $index => $dbGenre) {
                if ($dbGenre->name === $genre) {
                    $dbGenres[] = $dbGenre;
                    unset($dbGenres[$index]);
                    break;
                }
            }
        }
        $this->video->genres()->saveMany($dbGenres);
    }

    private function setCountries(array $countries)
    {
        foreach ($countries as $country) {
            $dbCountries[] = Country::firstOrCreate(['name' => $country]);
        }
        $this->video->countries()->saveMany($dbCountries);
    }

    private function setPeople(array $people)
    {
        $directors = $people['directors'];
        $screenwriters = $people['screenwriters'];
        unset($people);

        foreach ($directors as $director) {
            $people[$director] = 1;
        }
        foreach ($screenwriters as $screenwriter) {
            $people[$screenwriter] = isset($people[$screenwriter]) ? $people[$screenwriter] + 2 : 2;
        }
        foreach ($people as $person => $role) {
            $id = Person::firstOrCreate(['name' => $person])->id;
            $people[$id] = compact('role');
            unset($people[$person]);
        }
        $this->video->people()->attach($people);
    }
}