<x-filament-panels::page>
    @if ($this->generatedToken)
        <x-filament::section heading="Personal Access Token"
            description="Copy this token now. It will never be shown again.">
            <div x-data="{ token: '{{ $this->generatedToken }}' }" class="flex gap-2">
                <input x-model="token" readonly class="fi-input block w-full">

                <x-filament::button icon="heroicon-o-clipboard" x-on:click="navigator.clipboard.writeText(token)">
                    Copy
                </x-filament::button>

                <x-filament::button color="gray" icon="heroicon-o-x-mark" wire:click="$set('generatedToken', null)">
                    Dismiss
                </x-filament::button>
            </div>
        </x-filament::section>
    @endif

    {{ $this->table }}

</x-filament-panels::page>
