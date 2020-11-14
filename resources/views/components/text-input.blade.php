@props([
    'type' => "text",
    'label' => "",
    'required' => "false",
    'placeholder' => ""
])

<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}" class="block text-sm font-medium text-gray-700 leading-5">{{ $label }}</label>

    <div class="mt-1 rounded-md shadow-sm">
        <input
            {{ $attributes->whereStartsWith('wire:model') }}
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            type="{{ $type }}"
            @if($required) required @endif
            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror"
            placeholder="{{ $placeholder }}"
        />
    </div>

    @error($attributes->whereStartsWith('wire:model')->first())
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
