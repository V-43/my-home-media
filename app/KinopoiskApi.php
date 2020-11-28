<?php 

namespace App;

use App\Models\Video;
use Illuminate\Support\Facades\Http;

class KinopoiskApi {
    const TOKEN = 'a3e85f84cb97500978f4b179f7f222dc'; //@todo: перенести в .env

    public static function find(int $kinopoiskId):array
    {
        $searchResult = Http::get("https://api.kinopoisk.cloud/movies/$kinopoiskId/token/".self::TOKEN)
            ->json();
        $data['id_kinopoisk'] = $searchResult['id_kinopoisk'];
        $data['posterUrl'] = 'https:'.$searchResult['poster'];
        $data['title'] = $searchResult['title'];
        $data['titleAlt'] = $searchResult['title_alternative'];
        $data['year'] = $searchResult['year'];
        $data['countries'] = $searchResult['countries'];
        $data['genres'] = array_map('mb_strtolower', $searchResult['genres']);
        $data['directors'] = $searchResult['directors'];
        $data['screenwriters'] = $searchResult['screenwriters'];
        $data['description'] = str_replace('', '—', $searchResult['description']); //иначе тире отображается некорректно
        // $data['duration'] = $searchResult['collapse']['duration']; //"кто я" не выводит время ( == null)
        //"Ava" не выводит описание, год, постер, время
        //api.kinopoisk.cloud - говно, буду менять на парсеры либо использовать API TMDb

        return $data;
    }

    public static function findFilmsByKeyword(string $keyword)
    {
        $searchResults = Http::withHeaders([ 
            'Accept' => 'application/json',
            'Referer' => 'https://www.kinopoisk.ru/',
            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:81.0) Gecko/20100101 Firefox/81.0',
            'X-Requested-With' => 'XMLHttpRequest'
        ])->get('https://www.kinopoisk.ru/api/suggest/v2/?query='.$keyword)
        ->json()['suggest']['top'];
        
        if (!isset($searchResults['topResult'])) {
            return [
                'kinopoisk' => collect([]),
                'hasInDb' => collect([])
            ];
        }

        $topResult = $searchResults['topResult']['global'];
        if ($topResult['__typename'] === 'Film' && isset($topResult['productionYear']) && $topResult['productionYear'] <= date("Y")) {
            $results['kinopoisk'][] = [
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
                $results['kinopoisk'][] = [
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

        $results['kinopoisk'] = collect($results['kinopoisk']);
        $kinopoiskIds = $results['kinopoisk']->pluck('kinopoiskId');
        $hasInDb = Video::select('id', 'id_kinopoisk')->whereIn('id_kinopoisk', $kinopoiskIds)->get();
        $results['hasInDb'] = $results['kinopoisk']->whereIn('kinopoiskId', $hasInDb->pluck('id_kinopoisk'));
        $results['kinopoisk'] = $results['kinopoisk']->whereNotIn('kinopoiskId', $hasInDb->pluck('id_kinopoisk'));
        
        $hasInDb = $hasInDb->pluck('id', 'id_kinopoisk');
        $results['hasInDb'] = $results['hasInDb']->map(function ($item) use ($hasInDb) { 
            $item['id'] = $hasInDb[$item['kinopoiskId']]; //id фильма в нашей базе
            $item['href'] = route('movies.show', $item['id']);
            return $item;
        });

        $results['kinopoisk'] = $results['kinopoisk']->map(function ($item) {
            $item['href'] = route('movies.create', $item['kinopoiskId']);
            return $item;
        });

        // эти две строки для сброса ключей, иначе в формате JSON может оказаться не массив, а объект, и цикл x-for не будет работать
        $results['kinopoisk'] = $results['kinopoisk']->values(); 
        $results['hasInDb'] = $results['hasInDb']->values();

        return $results;
    }
}
