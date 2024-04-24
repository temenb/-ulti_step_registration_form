<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Word;
use App\Repositories\Interfaces\IRepository;

class Words implements IRepository
{
    /**
     * @param  ?Word  $word
     */
    public static function deleteOrphans(?Word $word): void
    {
        if ($word === null) {
            return;
        }

        $isUsed = (bool) ($word->fromTranslations->count() + $word->toTranslations->count());
        if (! $isUsed) {
            $word->delete();
        }
    }
}
