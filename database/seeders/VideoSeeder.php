<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\KinopoiskApi;
use App\Services\CreateVideoService;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kinopoiskIds = [
            779602, 258687, 453397, 251733,
            161085, 535341, 158786, 1108577,
            835086, 749540, 645276, 571896, 
        ];
        foreach ($kinopoiskIds as $id) {
            $videoData = KinopoiskApi::find($id);
            (new CreateVideoService)->make([
                'video' => [
                    'title' => $videoData['title'],
                    'title_alt' => $videoData['titleAlt'],
                    'year' => $videoData['year'],
                    'description' => $videoData['description'],
                    'id_kinopoisk' => $id,
                ],
                'people' => [
                    'directors' => $videoData['directors'],
                    'screenwriters' => $videoData['screenwriters'],
                ],
                'genres' => $videoData['genres'],
                'countries' => $videoData['countries'],
                'poster' => $videoData['posterUrl'],
            ]);
        }
    }
}
