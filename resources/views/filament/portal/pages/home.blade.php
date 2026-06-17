<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->getPortals() as $link)
            <x-filament::section>
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">
                        {{ $link->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $link->description }}
                    </p>

                    <x-filament::button tag="a" href="{{ $link->url }}" target="_blank">
                        Open
                    </x-filament::button>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
