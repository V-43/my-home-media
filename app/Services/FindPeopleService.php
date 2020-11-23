<?php 

namespace App\Services;

use App\Models\Person;

class FindPeopleService {
    public static function get(string $keyword, array $except=[]) {
        return Person::where('name', 'like', "$keyword%")
            ->whereNotIn('name', $except)
            ->take(5)
            ->get()
            ->pluck('name');
    }
}