<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Models\Vocabulary;
use App\Models\Word;
use App\Repositories\Interfaces\IRepository;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vocabularies implements IRepository
{
    public static function translationsList(Vocabulary $vocabulary): Collection
    {
        return $vocabulary->translations()
            ->join('words', 'words.uuid', '=', 'from_word_uuid')
            ->orderBy('words.word')
            ->get();
    }

    public static function delete(Vocabulary $vocabulary): bool
    {
        return DB::transaction(function () use ($vocabulary) {
            foreach ($vocabulary->translations as $transaction) {
                Translations::excludeFromVocabulary($vocabulary, $transaction);
            }

            return (bool) $vocabulary->delete();
        });
    }

    public static function createVocabularyFromText(
        string $title,
        string $fromLanguageUuid,
        string $toLanguageUuid,
        bool $private,
        string $image,
        string $userUuid,
        string $text
    ): bool {
        return DB::transaction(function () use (
            $title,
            $fromLanguageUuid,
            $toLanguageUuid,
            $private,
            $image,
            $userUuid,
            $text
        ) {
            preg_match_all('/\w+/u', $text, $result);

            $words = array_unique(array_shift($result));
            $vocabulary = Vocabulary::create([
                'title' => $title,
                'user_uuid' => $userUuid,
                'from_language_uuid' => $fromLanguageUuid,
                'to_language_uuid' => $toLanguageUuid,
                'private' => $private,
                'image' => $image,
            ]);

            foreach ($words as $word) {
                /** @var Word $fromWord */
                $fromWord = Word::firstOrCreate(['word' => $word, 'language_uuid' => $fromLanguageUuid]);

                $translations = Translation::where(['from_word_uuid' => $fromWord->uuid])
                    ->with(
                        ['vocabularies' =>
                            function (BelongsToMany $gelongToMany) use ($fromLanguageUuid, $toLanguageUuid) {
                                return $gelongToMany->where('from_language_uuid', $fromLanguageUuid)
                                    ->where('to_language_uuid', $toLanguageUuid);
                            }]
                    )->get();

                if ($translations->count()) {
                    foreach ($translations as $translation) {
                        $translation->vocabularies()->save($vocabulary);
                    }

                    return true;
                }
                /** @var Word $toWord */
                $toWord = Word::firstOrCreate(['word' => '', 'language_uuid' => $toLanguageUuid]);
                $translation = Translation::firstOrCreate(
                    ['from_word_uuid' => $fromWord->uuid],
                    ['to_word_uuid' => $toWord->uuid],
                );
                $vocabulary->translations()->save($translation);
            }

            return true;
        });
    }
}
