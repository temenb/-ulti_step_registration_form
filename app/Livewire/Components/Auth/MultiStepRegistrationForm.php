<?php

namespace App\Livewire\Components\Auth;

use App\Livewire\Forms\Auth\Register;
use App\Livewire\Forms\Exercise\Exercise as Form;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Registration')]

class MultiStepRegistrationForm extends Component
{
    public int $step = 1;

    public Register $form;

    public function nextStep()
    {
//        $attributes = match($this->step) {
//            1 => ['name','phone','email','password','password_confirmation'],
//            2 => ['dob', 'document_type', 'document_file'],
//            3 => ['country_id', 'address', 'city', 'note'],
//            default => [],
//        };
//        $this->form->valdate(attributes: $attributes);
//
//        dd($attributes);
        $this->step++;
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
