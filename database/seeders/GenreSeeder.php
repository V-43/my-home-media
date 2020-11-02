<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            ['name' => 'аниме'],
            ['name' => 'биография'],
            ['name' => 'боевик'],
            ['name' => 'вестерн'],
            ['name' => 'военный'],
            ['name' => 'детектив'],
            ['name' => 'детский'],
            ['name' => 'для взрослых'],
            ['name' => 'документальный'],
            ['name' => 'драма'],
            ['name' => 'игра'],
            ['name' => 'история'],
            ['name' => 'комедия'],
            ['name' => 'концерт'],
            ['name' => 'короткометражка'],
            ['name' => 'криминал'],
            ['name' => 'мелодрама'],
            ['name' => 'музыка'],
            ['name' => 'мультфильм'],
            ['name' => 'мюзикл'],
            ['name' => 'новости'],
            ['name' => 'приключения'],
            ['name' => 'реальное ТВ'],
            ['name' => 'семейный'],
            ['name' => 'спорт'],
            ['name' => 'ток-шоу'],
            ['name' => 'триллер'],
            ['name' => 'ужасы'],
            ['name' => 'фантастика'],
            ['name' => 'фильм-нуар'],
            ['name' => 'фэнтези'],
            ['name' => 'церемония'],
        ]);
    }
}
