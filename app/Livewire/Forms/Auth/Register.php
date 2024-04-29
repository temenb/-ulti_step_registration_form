<?php

namespace App\Livewire\Forms\Auth;

use App\Enums\IdentifierType;
use App\Models\Kyc;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use App\Repositories\Register as RegisterRepo;
use Livewire\Attributes\Locked;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class Register extends Form
{
    #[Locked]
    public int $step = 1;

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $dob = '';

    public string $document_type = IdentifierType::Passport->value;

    public ?TemporaryUploadedFile $document_file;

    public string $address = '';

    public string $city = '';

    public string $zip_code = '';

    public int $country_id = 0;

    public ?string $note;

    public function save(): void
    {
        $this->step = 0;
        $validatedData = $this->validate();
        /** @var TemporaryUploadedFile $documentFile */
        $documentFile = $this->document_file;
        /** @var string $filePath */
        $filePath = $documentFile->store(path: Kyc::DOCUMENT_PATH);
        $data = [
            'name'          => $validatedData['name'],
            'phone'         => $validatedData['phone'],
            'email'         => $validatedData['email'],
            'password'      => $validatedData['password'],
            'dob'           => $validatedData['dob'],
            'documentType'  => $validatedData['document_type'],
            'documentFile'  => $filePath,
            'address'       => $validatedData['address'],
            'city'          => $validatedData['city'],
            'zipCode'       => $validatedData['zip_code'],
            'countryId'     => $validatedData['country_id'],
            'note'          => $validatedData['note'],
        ];

        if (RegisterRepo::signUp(...$data)) {
            $this->reset();
        } else {
            Storage::delete($filePath);
        };
    }

    public function rules() // @phpstan-ignore-line
    {
        $rulesSet = [
            1 => [
                'name' => 'required|string|max:30',
                'phone' => 'required|unique:users|phone:INTERNATIONAL,UA',
                'email' => 'required|email|unique:users',
                'password' => 'required|max:20|confirmed',
                'password_confirmation' => 'required',
            ],
            2 => [
                'dob' => 'required|date',
                'document_file' => 'image|max:10000',
                'document_type' => [
                    'required',
                    new Enum(IdentifierType::class),
                ],
            ],
            3 => [
                'address' => 'required|string',
                'city' => 'required|string',
                'zip_code' => 'required|regex:/^\d{5}(?:[-\s]\d{4})?$/',
                'country_id' => 'required|exists:countries,id',
                'note' => 'nullable|string',
            ]
        ];

        if (isset($rulesSet[$this->step])) {
            return $rulesSet[$this->step];
        }

        $rules = [];
        foreach ($rulesSet as $set) {
            $rules = array_merge($rules, $set);
        }

        return $rules;
    }

    public function messages() // @phpstan-ignore-line
    {
        return [
            'phone.phone' => 'The phone field is not valid phone.',
            'dob.required' => 'The date of birth field is required.',
            'dob.date' => 'The date of birth field must be a valid date.',
            'zip_code.regex' => 'The zip_code field is not valid zip code.',
        ];
    }
}
