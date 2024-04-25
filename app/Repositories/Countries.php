<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Interfaces\IRepository;

class Countries implements IRepository
{
    static public function ddList() {
        $countries = Country::all();

        return $countries->pluck('name', 'id');
    }
}
