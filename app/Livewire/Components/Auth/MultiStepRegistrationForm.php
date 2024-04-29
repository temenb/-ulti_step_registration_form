<?php

namespace App\Livewire\Components\Auth;

use App\Livewire\Forms\Auth\Register;
use App\Models\Kyc;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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

    public function nextStep()
    {
        $this->form->step = $this->step;

        $this->form->validate();
        $this->step++;
        $this->latestStep = ($this->latestStep > $this->step)? $this->latestStep: $this->step;
    }

    public function changeStep(int $step)
    {
        if ($this->latestStep >= $step) {
            $this->step = $step;
        }
    }

    public function register()
    {
        $this->form->save();
        session()->flash('message', 'Registration is successful!');
        $this->reset(['step', 'latestStep']);
    }
}
