<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Interfaces\IRepository;
use Illuminate\Support\Collection;

class Countries implements IRepository
{

    public static function ddList(): Collection
    {
        $countries = Country::all();
        return $countries->pluck('name', 'id');
    }
}
