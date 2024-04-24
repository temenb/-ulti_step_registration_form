<?php

namespace App\Repositories;

use App\Models\Language;
use App\Repositories\Interfaces\IRepository;
use Illuminate\Support\Collection;

class Languages implements IRepository
{
    public static function ddList(): Collection
    {
        return Language::all();
    }
}
