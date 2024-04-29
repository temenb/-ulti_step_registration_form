<?php

namespace Tests\Feature\Livewire\Components\Auth;

use App\Enums\IdentifierType;
use App\Livewire\Components\Auth\MultiStepRegistrationForm;
use App\Models\Country;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;
use Faker\Factory as Faker;

class RegistrationTest extends TestCase
{
    public function test_can_not_go_to_next_step_because_of_validation()
    {
        Livewire::test(MultiStepRegistrationForm::class)
            ->assertSee('Personal information')
            ->assertSet('step', 1)
            ->call('nextStep')
            ->assertSet('step', 1);
    }

    public function test_can_go_to_next_step()
    {
        $pass = fake()->word;
        Livewire::test(MultiStepRegistrationForm::class)
            ->set('form.name', fake()->name)
            ->set('form.email', fake()->email)
            ->set('form.phone', fake()->numerify('066#######'))
            ->set('form.password', $pass)
            ->set('form.password_confirmation', $pass)
            ->call('nextStep')
            ->assertSet('step', 2)
            ->assertSee('ID confirmation');
    }

    public function test_can_go_to_previous_step()
    {
        $pass = fake()->word;
        Livewire::test(MultiStepRegistrationForm::class)
            ->set('form.name', fake()->name)
            ->set('form.email', fake()->email)
            ->set('form.phone', fake()->numerify('066#######'))
            ->set('form.password', $pass)
            ->set('form.password_confirmation', $pass)
            ->call('nextStep')
            ->assertSet('step', 2)
            ->call('changeStep', 1)
            ->assertSet('step', 1);
    }

    public function test_register()
    {
        $country = Country::firstOrCreate(['name' => 'Ukraine']);

        $pass = fake()->word;
        $data = [
            'name' => fake()->name,
            'email' => fake()->email,
            'phone' => fake()->numerify('066#######'),
            'password' => $pass,
            'password_confirmation' => $pass,
            'dob' => '01/01/2020',
            'document_type' => IdentifierType::Passport->value,
            'document_file' => UploadedFile::fake()->image('text.png'),
            'country_id' => $country->id,
            'city' => fake()->city,
            'address' => fake()->address,
            'zip_code' => fake()->postcode,
            'note' => fake()->sentence,
        ];

        Livewire::test(MultiStepRegistrationForm::class)
            ->set('form.name', $data['name'])
            ->set('form.email', $data['email'])
            ->set('form.phone', $data['phone'])
            ->set('form.password', $data['password'])
            ->set('form.password_confirmation', $data['password_confirmation'])
            ->set('form.dob', $data['dob'])
            ->set('form.document_type', $data['document_type'])
            ->set('form.document_file', $data['document_file'])
            ->set('form.country_id', $data['country_id'])
            ->set('form.city', $data['city'])
            ->set('form.address', $data['address'])
            ->set('form.zip_code', $data['zip_code'])
            ->set('form.note', $data['note'])
            ->call('register');

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'note' => $data['note'],
            'phone' => $data['phone'],
        ]);
        $this->assertDatabaseHas('kycs', [
            'dob' => $data['dob'],
            'document_type' => $data['document_type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'country_id' => $data['country_id'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'city' => $data['city'],
        ]);
    }
}
