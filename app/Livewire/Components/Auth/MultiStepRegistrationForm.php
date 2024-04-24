<?php

namespace App\Livewire\Components\Auth;

use App\Livewire\Forms\Auth\Register;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Registration')]

class MultiStepRegistrationForm extends Component
{
    public int $step = 1;
    public int $currentStep = 1;

    public Register $form;

    public function nextStep()
    {
        $attributes = match($this->step) {
//            1 => ['name','phone','email','password'],
            2 => ['dob', 'document_type', 'document_file'],
            3 => ['country_id', 'address', 'city', 'note'],
            default => [],
        };
        foreach ($attributes as $attribute) {
            $this->form->validateOnly($attribute);
        }

        $this->step++;
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function register()
    {
        $this->form->persist();
        session()->flash('message', 'Registration is successful!');
    }
}
