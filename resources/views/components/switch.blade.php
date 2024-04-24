<div x-data="{ switchOn: $wire.{{ $attributes['wire:model'] }} }">
    <button
        x-ref="switchButton"
        type="button"
        @click="switchOn = ! switchOn; $wire.{{ $attributes['wire:model'] }} = switchOn"
        :class="switchOn ? 'bg-blue-600' : 'bg-neutral-200'"
        class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
        x-cloak>
        <span :class="switchOn ? 'translate-x-[18px]' : 'translate-x-0.5'" class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
    </button>

    <label @click="$refs.switchButton.click(); $refs.switchButton.focus()" :id="$id('switch')"
           :class="{ 'text-blue-600': switchOn, 'text-gray-400': ! switchOn }"
           class="text-sm select-none"
           x-cloak>
        {{ $label }}
    </label>
</div>
