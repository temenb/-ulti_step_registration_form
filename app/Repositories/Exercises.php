<?php

namespace App\Repositories;

use App\Models\Exercise;
use App\Models\Translation;
use App\Models\User;
use App\Models\Word;
use App\Repositories\Interfaces\IRepository;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Wireable;
use Log;

class Exercises implements IRepository, Wireable
{
    private int $step = 0;

    private User $user;

    const DIRECTION_DIRECT = 'direct';

    const DIRECTION_REVERSE = 'reverse';

    const DIRECTIONS = [
        self::DIRECTION_DIRECT => self::DIRECTION_REVERSE,
        self::DIRECTION_REVERSE => self::DIRECTION_DIRECT,
    ];

    const AMOUNT_EXERCISES_FOR_SCORE = 10;

    public function __construct(
        private Collection $translations,
        private string $direction = self::DIRECTION_DIRECT,
        ?User $user = null
    ) {
        /** @var User $user */
        $user = $user ?? auth()->user();
        $this->user = $user;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): void
    {
        if (! in_array($direction, self::DIRECTIONS)) {
            throw new Exception;
        }
        $this->direction = $direction;
    }

    /**
     * @throws Exception
     */
    private function switchDirection(): void
    {
        if (empty(self::DIRECTIONS[$this->direction])) {
            throw new Exception;
        }
        $this->direction = self::DIRECTIONS[$this->direction];
    }

    public function reset(): void
    {
        $this->translations = $this->shuffle($this->translations);
        $this->setStep(0);
    }

    public function translations(): Collection
    {
        return $this->translations;
    }

    private function shuffle(Collection $collection): Collection
    {
        $array = $collection->all();
        shuffle($array);

        return collect($array);
    }

    public static function createFromTranslationsInCharge(
        string $direction = self::DIRECTION_DIRECT,
        ?User $user = null,
        int $limit = 10
    ): self {
        if (! $user) {
            /** @var User $user */
            $user = auth()->user();
        }
        $translations = $user->translations()
            ->wherePivot('next_execution', '<=', now())->limit($limit)->get();

        return new self($translations, $direction, $user);
    }

    private function setStep(int $step): void
    {
        $this->step = $step;
    }

    public function question(): string
    {
        $translation = $this->currentTranslation();
        /** @var Word $toWord */
        $toWord = $translation->toWord;
        /** @var Word $fromWord */
        $fromWord = $translation->fromWord;

        return match ($this->direction) {
            self::DIRECTION_DIRECT => $fromWord->word,
            self::DIRECTION_REVERSE => $toWord->word,
            default => '',
        };
    }

    public function answer(): string
    {
        $translation = $this->currentTranslation();
        /** @var Word $fromWord */
        $fromWord = $translation->fromWord;
        /** @var Word $toWord */
        $toWord = $translation->toWord;

        return match ($this->direction) {
            self::DIRECTION_DIRECT => $toWord->word,
            self::DIRECTION_REVERSE => $fromWord->word,
            default => '',
        };
    }

    public function setResult(string $answer): bool
    {
        $state = strtolower($answer) === strtolower($this->answer());

        $this->createExcerciseModel($state);

        return $state;
    }

    private function createExcerciseModel(bool $state): void
    {
        try {
            DB::beginTransaction();
            Exercise::create([
                'translation_uuid' => $this->currentTranslation()->uuid,
                'user_uuid' => $this->user->uuid,
                'state' => $state,
            ]);
            $translation = $this->currentTranslation();

            $sExercisesCount = $translation->exercises()->orderBy('created_at')
                ->limit(self::AMOUNT_EXERCISES_FOR_SCORE)->get()
                ->filter(function ($item) {
                    return $item['state'];
                })->count();
            $score = ($sExercisesCount ? $sExercisesCount : 0.1) / self::AMOUNT_EXERCISES_FOR_SCORE;
            $dayInSeconds = 24 * 60 * 60;
            $interval = round($dayInSeconds * (pow(2, (0.5 + $score)) - 1));
            $nextExecution = (new Carbon)->modify("+$interval seconds");
            $translation->users()->updateExistingPivot($this->user->id, ['next_execution' => $nextExecution]);
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }
    }

    public function currentTranslation(): Translation
    {
        return $this->translations->get($this->step); // @phpstan-ignore-line
    }

    public function switchStep(): void
    {
        $this->step++;

        if ($this->step >= $this->translations()->count()) {
            $this->step = 0;
            $this->switchDirection();
        }
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function toLivewire(): array // @phpstan-ignore-line
    {
        return [
            'translations' => $this->translations(),
            'direction' => $this->direction,
            'step' => $this->step,
            'user' => $this->user,
        ];
    }

    public static function fromLivewire($value): self // @phpstan-ignore-line
    {
        $lesson = new self($value['translations'], $value['direction'], $value['user']);
        $lesson->setStep($value['step']);

        return $lesson;
    }
}
