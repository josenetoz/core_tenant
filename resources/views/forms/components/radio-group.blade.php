@php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field" x-data="{
    state: $wire.$entangle('{{ $statePath }}')
}">
    <div class="flex w-full gap-3">
        @foreach ($getOptions() as $value => $label)
            @php
                $shouldOptionBeDisabled = $isDisabled || $isOptionDisabled($value, $label);
            @endphp

            <label class="flex flex-1 cursor-pointer">
                <div class="flex w-full items-center gap-x-3 rounded-lg p-6 transition-all ring-1 fi-color-custom"
                    :class="state == '{{ $value }}' ? 'ring-5 ring-custom-600 dark:ring-custom-500' :
                        'ring-gray-200 dark:ring-gray-700'"
                    @style([\Filament\Support\get_color_css_variables('primary', shades: [500, 600])])>
                    <x-filament::input.radio :valid="!$errors->has($statePath)" :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())->merge(
                        [
                            'disabled' => $shouldOptionBeDisabled,
                            'id' => $id . '-' . $value,
                            'name' => $id,
                            'value' => $value,
                            'wire:loading.attr' => 'disabled',
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ],
                        escape: false,
                    )" />

                    <span class="font-medium text-xl text-gray-950 dark:text-white">
                        {{ $label }}
                    </span>

                    @if ($hasBadge($value))
                        <x-filament::badge class="ml-auto px-6 py-2">
                            {{ $getBadge($value) }}
                        </x-filament::badge>
                    @endif
                </div>
            </label>
        @endforeach
    </div>
</x-dynamic-component>
