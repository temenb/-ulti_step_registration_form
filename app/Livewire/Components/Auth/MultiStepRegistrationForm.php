<?php

namespace App\Livewire\Components\Auth;

use App\Livewire\Forms\Auth\Register;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Title('Registration')]

class MultiStepRegistrationForm extends Component
{
    use WithFileUploads;
    public int $step = 1;
    public int $currentStep = 1;

    #[Validate('image|max:10000')]
    public ?TemporaryUploadedFile $document_file = null;

    public Register $form;

    public function nextStep()
    {
        $attributes = match($this->step) {
            1 => ['name','phone','email','password'],
            2 => ['dob', 'document_type', 'document_file'],
            3 => ['country_id', 'address', 'city', 'note'],
            default => [],
        };

        foreach ($attributes as $attribute) {
            $this->form->validateOnly($attribute);
        }

        $this->step++;
        $this->currentStep = $this->step;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function register()
    {

//        if (! empty($this->image)) {
//            /** @var string $path */
//            $this->form->imagepath = $this->image->store(path: Vocabulary::IMAGES_PATH);
//        }
        $this->form->persist();
        session()->flash('message', 'Registration is successful!');
    }
}
