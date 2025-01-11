<x-filament-widgets::widget >
    <form wire:submit="create">
    {{ $this->form }}
        {{-- <button type="submit">
            Submit
        </button> --}}

        <div class="px-6 mx-auto">
            <x-filament::button 
                class="w-full py-4 bg-emerald-700 hover:bg-emerald-800"
                type="submit"
                form="create"
                color="danger"
            >
            {{ __('ticket::txt.click-here-to-submit-a-new-ticket') }}
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-widgets::widget >
