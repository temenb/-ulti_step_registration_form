<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Attributes\Validate;
use Livewire\Form;

class Register extends Form
{

    #[Validate('required|string')]
    public $name;

    #[Validate('required|phone')]
    public $phone;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|max:20|confirmed')]
    public $password;

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
