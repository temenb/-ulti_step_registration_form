<?php

namespace App\Livewire\Forms\Auth;

use App\Enums\IdentifierType;
use Illuminate\Validation\Rules\Enum;
use App\Repositories\Register as RegisterRepo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class Register extends Form
{

    #[Validate('required|string')]
    public string $name = 'asdf';

    #[Validate('required|phone')]
    public string $phone = '+380661215732';

    #[Validate('required|email')]
    public string $email = 'temenb@gmail.com';

    #[Validate('required|max:20|confirmed')]
    public string $password = '123';

    public string $password_confirmation = '123';

    #[Validate('required|date')]
    public string $dob;

    #[Validate]
    public string $document_type = IdentifierType::passport->name;

    #[Validate('required|string')]
    public string $document_file = 'asdf';

    #[Validate('required|string')]
    public string $address = '123';

    #[Validate('required|string')]
    public string $city = '123';

    #[Validate('required|regex:/^\d{5}(?:[-\s]\d{4})?$/')]
    public string $zip_code = '12334';

    #[Validate('required|exists:country')]
//    public int $country = 0;
    public int $country = 1;

    #[Validate('string')]
    public $note;

    public function persist()
    {
        $data = $this->validate();

        dd($data);
        RegisterRepo::signUp(...$data);
    }

    public function rules()
    {
        return [
            'document_type' => [
                'required',
                new Enum(IdentifierType::class),
            ],
        ];
    }
}
