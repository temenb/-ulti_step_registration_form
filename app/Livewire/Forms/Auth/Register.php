<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Translation;
use App\Models\Vocabulary;
use App\Repositories\Exercises;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class Register extends Form
{

    #[Validate('required|string')]
    public $name;

//    #[Validate('required')]
    public $phone;

    #[Validate('required')]
    public $email;

    #[Validate('required')]
    public $password;

    #[Validate('required')]
    public $password_confirmation;

    #[Validate('required')]
    public $dob;

    #[Validate('required')]
    public $document_type;

    #[Validate('required')]
    public $document_file;

    #[Validate('required')]
    public $address;

    #[Validate('required')]
    public $city;

    #[Validate('required')]
    public $zip_code;

    #[Validate('required')]
    public $country;

    #[Validate('required')]
    public $note;

    public function persist()
    {

        $this->validate();
    }
}
