<?php

namespace App\Livewire\Components\Auth;

use App\Livewire\Forms\Auth\Register;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Registration')]

class MultiStepRegistrationForm extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $step = 1;

    #[Locked]
    public int $latestStep = 1;

    public Register $form;

    public function nextStep(): void
    {
        $this->form->step = $this->step;

        $this->form->validate();
        $this->step++;
        $this->latestStep = ($this->latestStep > $this->step)? $this->latestStep: $this->step;
    }

    public function changeStep(int $step): void
    {
        if ($this->latestStep >= $step) {
            $this->step = $step;
        }
    }

    public function register(): void
    {
        $this->form->save();
        session()->flash('message', 'Registration is successful!');
        $this->reset(['step', 'latestStep']);
    }
}
