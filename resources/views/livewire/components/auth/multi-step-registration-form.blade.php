<!-- livewire/multi-step-registration-form.blade.php -->

<div>
    @if ($step === 1)
        <h2>{{ __('Personal information') }}</h2>
        <input type="text" wire:model="form.name" placeholder="{{ __('Name') }}">
        @error('form.name') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.phone" placeholder="{{ __('Phone') }}">
        @error('form.phone') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.email" placeholder="{{ __('Email') }}">
        @error('form.email') <span class="error">{{ $message }}</span> @enderror

        <input type="password" wire:model="form.password" placeholder="{{ __('Password') }}">
        @error('form.password') <span class="error">{{ $message }}</span> @enderror

        <input type="password" wire:model="form.password_confirmation" placeholder="{{ __('Password confirmation') }}">
        @error('form.password_confirmation') <span class="error">{{ $message }}</span> @enderror

        <button wire:click="nextStep">{{ __('Continue') }}</button>
    @elseif ($step === 2)
        <h2>{{ __('ID confirmation') }}</h2>
        <input type="text" wire:model="form.dob" placeholder="{{ __('Date of birth') }}">
        @error('form.dob') <span class="error">{{ $message }}</span> @enderror

        <div>
            @foreach(\App\Enum\IdentifierType::ENUM as $key => $value)
                <input id="identifier-{{$key}}" type="radio" wire:model="form.document_type" value="{{ $key }}">
                <label for="identifier-{{$key}}">{{ __($value) }}</label>
            @endforeach
        </div>
        @error('form.document_type') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.document_file" placeholder="Номер документа">
        @error('form.document_file') <span class="error">{{ $message }}</span> @enderror

        <button wire:click="nextStep">{{ __('Continue') }}</button>
    @elseif ($step === 3)
        <h2>Адрес</h2>
        <input type="text" wire:model="form.address" placeholder="{{ __('Address') }}">
        @error('form.address') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.city" placeholder="{{ __('City') }}">
        @error('form.city') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.zip_code" placeholder="{{ __('Zip code') }}">
        @error('form.zip_code') <span class="error">{{ $message }}</span> @enderror

        <input type="text" wire:model="form.country" placeholder="{{ __('Country') }}">
        @error('form.country') <span class="error">{{ $message }}</span> @enderror

        <textarea wire:model="form.note" placeholder="Note"></textarea>
        @error('form.note') <span class="error">{{ $message }}</span> @enderror

        <button wire:click="register">{{ __('Complete sign up') }}</button>
    @else
        success
    @endif

    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif
</div>
