<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Enums\TicketTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectedStatus = '';
    public $resolvedTicketsCount;
    public $perPage = 3;
    public $expandedTickets = [];
    public $filteredCount = 0;

    public function toggleTicket($ticketId)
    {
        if (in_array($ticketId, $this->expandedTickets)) {
            $this->expandedTickets = array_diff($this->expandedTickets, [$ticketId]);
        } else {
            $this->expandedTickets[] = $ticketId;
        }
    }

    public function mount()
    {
        $this->resolvedTicketsCount = Ticket::query()
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->count();
    }

    public function loadMore()
    {
        $this->perPage += 3;
    }

    public function updatedSelectedCategories($value)
    {
        Log::error('Categories updated', ['value' => $this->selectedCategories]);
        $this->dispatch('categoryFilterUpdated')
            ->to(\Modules\Ticket\Filament\Widgets\TicketsMapWidget::class);
    }

    public function clearCategories()
    {
        $this->selectedCategories = [];
        $this->dispatch('categoryFilterUpdated')
            ->to(\Modules\Ticket\Filament\Widgets\TicketsMapWidget::class);
    }

    private function getAddress($lat, $lon): string
    {
        Log::error('Getting address for coordinates', ['lat' => $lat, 'lon' => $lon]);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'FixCity/1.0 (your@email.com)', // Replace with your actual app name and contact email
                'Accept-Language' => 'it' // For Italian results
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lon,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            Log::error('API Response', ['response' => $response->json()]);

            if ($response->successful()) {
                $data = $response->json();
                $address = $data['display_name'] ?? 'Indirizzo non trovato';
                Log::error('Address found', ['address' => $address]);
                return $address;
            }
        } catch (\Exception $e) {
            Log::error('Error getting address', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lat' => $lat,
                'lon' => $lon
            ]);
        }

        Log::error('Returning default address');
        return 'Indirizzo non trovato';
    }

    public function with(): array
    {
        $categories = collect(TicketTypeEnum::cases())->map(function ($type) {
            $count = Ticket::where('type', $type->value)
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->count();

            return [
                'label' => $type->getLabel(),
                'value' => $type->value,
                'count' => $count
            ];
        });

        $query = Ticket::query()
            ->select('id', 'name', 'type', 'content', 'created_at', 'latitude', 'longitude')
            ->with('media')
            ->latest();

        if (count($this->selectedCategories) > 0) {
            $query->whereIn('type', $this->selectedCategories);
        }

        $this->filteredCount = $query->count();

        $tickets = $query->take($this->perPage)->get();

        $hasMorePages = Ticket::count() > $this->perPage;

        return [
            'categories' => $categories,
            'tickets' => $tickets,
            'hasMorePages' => $hasMorePages,
            'filteredCount' => $this->filteredCount
        ];
    }
}
?>

@volt('ticket_list')
<div class="py-4 space-y-12 text-gray-950">
    <!-- Breadcrumbs -->
    <section class="max-w-screen-lg px-4 mx-auto">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a class="link-success">Home</a></li>
                <li>Elenco segnalazioni</li>
            </ul>
        </div>
    </section>
    <!-- Title -->
    <section class="max-w-screen-lg px-4 mx-auto">
        <h1 class="mb-2 text-3xl font-bold lg:text-5xl">Elenco segnalazioni</h1>
        <p>Negli ultimi 12 mesi sono state risolte {{ $resolvedTicketsCount }} segnalazioni.</p>
    </section>
    <!-- Divider -->
    <div class="max-w-screen-xl px-4 mx-auto">
        <hr class="my-4" />
    </div>
    <!-- Category & Maps -->
    <section class="max-w-screen-xl px-4 mx-auto">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-24">
            <div class="space-y-4">
                <h4 class="font-bold text-emerald-800">CATEGORIA</h4>
                <div>
                    @foreach($categories as $category)
                    <div class="form-control">
                        <label class="cursor-pointer label !justify-start space-x-4">
                            <input
                                type="checkbox"
                                class="checkbox checkbox-sm"
                                wire:model.live="selectedCategories"
                                value="{{ $category['value'] }}" />
                            <span class="label-text text-gray-950">{{ $category['label'] }} ({{ $category['count'] }})</span>
                        </label>
                    </div>
                    @endforeach

                    @if(count($selectedCategories) > 0)
                    <div class="mt-2">
                        <button
                            wire:click="clearCategories"
                            class="text-sm text-emerald-600 hover:text-emerald-800">
                            Mostra tutte le categorie
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-span-2 space-y-4">
                <div class="flex justify-between text-sm">
                    <div>{{ $filteredCount }} Risultati</div>
                    <div>
                        @if(count($selectedCategories) > 0)
                        <button
                            wire:click="clearCategories"
                            class="text-emerald-800">
                            Rimuovi tutti i filtri
                        </button>
                        @endif
                    </div>
                </div>
                <hr />
                <div role="tablist" class="grid-cols-2 tabs tabs-bordered">
                    <input type="radio" name="my_tabs_1" role="tab" class="text-lg text-gray-950 border-0 rounded-none tab focus:!bg-transparent hover:!bg-transparent checked:bg-transparent focus:ring-0 focus:!border-emerald-800" aria-label="Mappa" checked="checked" />
                    <div role="tabpanel" class="py-8 space-y-6 tab-content">
                        @livewire(\Modules\Ticket\Filament\Widgets\TicketsMapWidget::class, [
                        'categoryFilter' => $selectedCategories
                        ], key('map-' . implode('-', $selectedCategories)))
                    </div>
                    <input type="radio" name="my_tabs_1" role="tab" class="text-lg text-gray-950 border-0 rounded-none tab focus:!bg-transparent hover:!bg-transparent checked:bg-transparent focus:ring-0 focus:!border-emerald-800" aria-label="Elenco" />
                    <div role="tabpanel" class="py-8 space-y-4 tab-content">
                        @foreach($tickets as $ticket)
                        <x-filament::section>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold">{{ $ticket->name }}</h3>
                                <div class="space-y-2">
                                    <p>Tipologia di segnalazione</p>
                                    <p><strong>{{ $ticket->type->getLabel() }}</strong></p>
                                </div>
                                @if(in_array($ticket->id, $expandedTickets))
                                <div class="space-y-4">
                                    <!-- Location Information -->

                                    <div class="space-y-2">
                                        <p><strong>Indirizzo:</strong><br>
                                            @if($ticket->latitude && $ticket->longitude)
                                            {{ $ticket->address }}
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Rest of your expanded ticket content -->
                                    <div class="space-y-2">
                                        <p class="font-medium">Dettaglio</p>
                                        <p>{{ $ticket->content }}</p>
                                    </div>


                                    <div class="space-y-2">
                                        <p class="font-medium">Immagini</p>
                                        @if($ticket->media->count() > 0)
                                        <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                                            @foreach($ticket->media as $media)
                                            <div class="relative aspect-square">
                                                <img
                                                    src="{{ $media->getUrl() }}"
                                                    alt="Immagine segnalazione"
                                                    class="object-cover w-full h-full rounded-lg" />
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <hr class="border-gray-300" />
                                <button wire:click="toggleTicket({{ $ticket->id }})" class="btn btn-neutral">
                                    <div class="text-white">{{ in_array($ticket->id, $expandedTickets) ? 'Mostra meno' : 'Mostra tutto' }}</div>
                                    <x-bi-arrow-{{ in_array($ticket->id, $expandedTickets) ? 'up' : 'down' }}-circle-fill class="size-4" />
                                </button>
                            </div>
                        </x-filament::section>
                        @endforeach

                        @if($hasMorePages)
                        <div class="py-4 text-center">
                            <button wire:click="loadMore" class="btn btn-outline text-emerald-600 btn-lg">
                                Carica altre segnalazioni
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold lg:text-4xl">Fai una segnalazione</h2>
                    <p>Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE.</p>
                    <br />
                    <a href="{{ route('ticket.create', ['lang'=>$lang]) }}" class="text-white btn btn-neutral">Segnala disservizio</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumbs -->
    <div class="px-4 py-12 bg-emerald-700">
        <section class="max-w-screen-md p-6 px-4 mx-auto space-y-4 bg-white rounded-lg lg:p-12">
            <h4 class="text-2xl font-bold">Quanto sono chiare le informazioni su questa pagina?</h4>
            <div class="rating">
                <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 checked:!bg-orange-400" />
                <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 checked:!bg-orange-400" />
                <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 checked:!bg-orange-400" />
                <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 checked:!bg-orange-400" />
                <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 checked:!bg-orange-400" />
            </div>
        </section>
    </div>
    <div class="py-12 !my-0 bg-gray-50 px-4">
        <section class="max-w-screen-md p-6 px-4 mx-auto space-y-4 bg-white rounded-lg lg:p-12">
            <h4 class="text-2xl font-bold">Contatta il comune</h4>
            <ul class="space-y-2">
                <li>
                    <a href="" class="flex items-center space-x-2 text-emerald-700">
                        <x-heroicon-o-link class="size-5" />
                        <div>Leggi le domande frequenti</div>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center space-x-2 text-emerald-700">
                        <x-heroicon-o-link class="size-5" />
                        <div>Richiedi assistenza</div>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center space-x-2 text-emerald-700">
                        <x-heroicon-o-link class="size-5" />
                        <div>Chiama il numero verde 05 0505</div>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center space-x-2 text-emerald-700">
                        <x-heroicon-o-link class="size-5" />
                        <div>Prenota appuntamento</div>
                    </a>
                </li>
            </ul>
        </section>
    </div>
</div>
@endvolt