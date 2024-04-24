<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\Translation;
use App\Models\Vocabulary;
use App\Models\Word;
use App\Repositories\Interfaces\IRepository;
use DB;

class Translations implements IRepository
{
    public static function patchTranslation(
        string $fromWord,
        string $toWord,
        Translation $translation,
        Vocabulary $vocabulary
    ): ?Translation {
        return DB::transaction(function () use ($vocabulary, $translation, $fromWord, $toWord) {
            /** @var Language $fromLanguage */
            $fromLanguage = $vocabulary->fromLanguage;
            /** @var Language $toLanguage */
            $toLanguage = $vocabulary->toLanguage;
            $fromWordModel = Word::firstOrCreate(['word' => trim($fromWord), 'language_uuid' => $fromLanguage->uuid]);
            $toWordModel = Word::firstOrCreate(['word' => trim($toWord), 'language_uuid' => $toLanguage->uuid]);
            self::excludeFromVocabulary($vocabulary, $translation);
            /** @var Translation $translation */
            $translation = Translation::firstOrCreate([
                'from_word_uuid' => $fromWordModel->uuid,
                'to_word_uuid' => $toWordModel->uuid,
            ]);
            $translation->vocabularies()->save($vocabulary);

            return $translation;
        });
    }

    public static function excludeFromVocabulary(Vocabulary $vocabulary, Translation $translation): bool
    {
        return DB::transaction(function () use ($vocabulary, $translation) {
            if (! $translation->exists) {
                return true;
            }
            $vocabulary->translations()->detach($translation->uuid);
            $fromWord = $translation->fromWord;
            $toWord = $translation->toWord;
            if (! $translation->vocabularies()->count()) {
                $translation->delete();
            }
            Words::deleteOrphans($fromWord);
            Words::deleteOrphans($toWord);

            return true;
        });
    }
}
