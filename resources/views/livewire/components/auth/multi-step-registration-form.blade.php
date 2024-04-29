<div class="flex">
    <div class="w-1/4 p-4 bg-gray-200">
        <ul>
            @foreach(
                [
                    1 => 'Personal information',
                    2 => 'ID confirmation',
                    3 => 'Address',
                ] as $key => $title
            )
                <li class="mb-2 flex items-center" :class="$wire.step == {{$key}}? 'font-bold' : 'opacity-50'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="{{$key}} <= $wire.latestStep? 'text-blue-500': 'text-gray-500'" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                        <circle cx="10" cy="10" r="9" stroke-width="2" fill="none"/>
                        <path d="M6 10l2 2 6-6"/>
                    </svg>
                    <a href="#" wire:click.prevent="changeStep({{ $key }})">{{ __($title) }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="w-3/4 p-4 flex justify-center items-center">
        <div class="max-w-md w-full">
            @if (session()->has('message'))
                <div class="bg-green-500 text-white text-lg font-bold p-4 mb-4 rounded-full">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-500 text-white text-lg font-bold p-4 mb-4 rounded-full">{{ session('error') }}</div>
            @endif

            <div x-show="$wire.step == 1">
                <h2 class="text-xl font-bold">{{ __('Personal information') }}</h2>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }} *</label>
                    <input type="text" id="name" wire:model="form.name" class="mt-1 @error('form.name') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }} *</label>
                    <input type="text" id="phone" wire:model="form.phone" class="mt-1 @error('form.phone') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }} *</label>
                    <input type="text" id="email" wire:model="form.email" class="mt-1 @error('form.email') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }} *</label>
                    <input type="password" id="password" wire:model="form.password" class="mt-1 @error('form.password') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Password confirmation') }} *</label>
                    <input type="password" id="password_confirmation" wire:model="form.password_confirmation" class="mt-1 @error('form.password_confirmation') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button wire:click="nextStep" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('Continue') }}</button>
            </div>
            <div x-show="$wire.step == 2">
                <h2 class="text-xl font-bold">{{ __('ID confirmation') }}</h2>

                <div class="mb-4"
                    x-on:change="$wire.form.dob = $event.target.value"
                    x-init="
                        new Pikaday({
                            toString(date, format) {
                                // you should do formatting based on the passed format,
                                // but we will just return 'M/D/YYYY' for simplicity
                                const day = ('0' + date.getDate()).slice(-2);
                                const month = ('0' + (date.getMonth() + 1)).slice(-2);
                                const year = date.getFullYear();
                                return `${day}.${month}.${year}`;
                            },
                            parse(dateString, format) {
                                // dateString is the result of `toString` method
                                const parts = dateString.split('.');
                                const day = parseInt(parts[0], 10);
                                const month = parseInt(parts[1], 10) - 1;
                                const year = parseInt(parts[2], 10);
                                return new Date(year, month, day);
                            },
                            field: $refs.input,
                            maxDate: new Date()
                        });
                    ">
                    <label for="dob" class="block text-sm font-medium text-gray-700">{{ __('Date of Birth') }} *</label>

                    <input
                        x-ref="input"
                        type="text"
                        wire:model="form.dob"
                        class="mt-1 @error('form.dob') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                    @error('form.dob') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Document Type') }} *</label>
                    <div>
                        @foreach(\App\Enums\IdentifierType::cases() as $item)
                            <div class="flex items-center">
                                <input id="identifier-type-{{$item->value}}" type="radio" wire:model="form.document_type" value="{{ $item->value }}" class="class="mt-1 @error('form.document_type') border-red-500 @enderror focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="identifier-type-{{$item->value}}" class="ml-2 block text-sm text-gray-900">{{ __(\App\Enums\IdentifierType::title($item)) }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('form.document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4" x-show="$wire.form.document_type">
                    <label for="document_file" class="block text-sm font-medium text-gray-700">{{ __('Document File') }} *</label>
                    <div class="mt-1 flex justify-center items-center">
                        @if (!empty($form->document_file) && $form->document_file->isPreviewable())
                            <img src="{{ $form->document_file->temporaryUrl() }}" class="w-1/2; h-1/2;" />
                        @endif
                    </div>
                    <div class="mt-1 flex justify-center items-center">
                        <label for="document_file" class="cursor-pointer flex items-center px-4 py-2 bg-white text-sm font-medium leading-5 text-indigo-600 rounded-md border border-gray-300 shadow-sm hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            {{ __('Upload') }}
                        </label>
                        <input type="file" id="document_file" wire:model="form.document_file" class="hidden">
                    </div>
                    @error('form.document_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button wire:click="nextStep" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('Continue') }}</button>
            </div>


            <div x-show="$wire.step == 3">
                <h2 class="text-xl font-bold">{{ __('Address') }}</h2>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">{{ __('Address') }} *</label>
                    <input type="text" id="address" wire:model="form.address" class="mt-1 @error('form.address') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }} *</label>
                    <input type="text" id="city" wire:model="form.city" class="mt-1 @error('form.city') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="zip_code" class="block text-sm font-medium text-gray-700">{{ __('Zip Code') }} *</label>
                    <input type="text" id="zip_code" wire:model="form.zip_code" class="mt-1 @error('form.zip_code') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('form.zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="country_id" class="block text-sm font-medium text-gray-700">{{ __('Country') }} *</label>
                    <select id="country_id" wire:model="form.country_id" class="mt-1 @error('form.country_id') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <option value="0" disabled>{{ __('Select Country') }}</option>
                        @foreach (\App\Repositories\Countries::ddList() as $countryKey => $countryName)
                            <option value="{{ $countryKey }}">{{ $countryName }}</option>
                        @endforeach
                    </select>
                    @error('form.country_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700">{{ __('Note') }}</label>
                    <textarea id="note" wire:model="form.note" class="mt-1 @error('form.note') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    @error('form.note') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button wire:click="register" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('Complete Sign Up') }}</button>
            </div>

        </div>
    </div>
</div>
