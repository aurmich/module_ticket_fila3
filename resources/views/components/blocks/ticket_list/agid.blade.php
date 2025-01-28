<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedStatus = '';

    public function with(): array
    {
        return [
            'categories' => [
                "Acqua, allagamenti, problemi fognari (22)",
                "Ambiente, inquinamento, protezione ambientale (14)",
                "Arredo urbano (7)",
                "Disinfestazione, derattizazione, animali randagi (208)",
                "Igiene urbana, rifiuti, pulizia e decoro (321)",
                "Manutenzione immobili, edifici pubblici, scuole, barriere architettoniche, cimiteri (302)",
                "Ordine pubblico, disturbo della quiete (302)",
                "Parchi e verde pubblico (302)",
                "Servizi del comune (302)",
                "Sicurezza, degrado urbano e sociale (302)",
                "Strade, marciapiedi, segnaletica e viabilità (302)"
            ],
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
        <p>Negli ultimi 12 mesi sono state risolte 73 segnalazioni.</p>
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
                    <div class=" form-control">
                        <label class="cursor-pointer label !justify-start space-x-4">
                            <input type="checkbox" class="checkbox checkbox-sm" />
                            <span class="label-text text-gray-950">{{ $category }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-span-2 space-y-4">
                <div class="flex justify-between text-sm">
                    <div>645 Risultati</div>
                    <div>
                        <a class="text-emerald-800" href="">Rimuovi tutti i filtri</a>
                    </div>
                </div>
                <hr />
                <div role="tablist" class="grid-cols-2 tabs tabs-bordered">
                    <input type="radio" name="my_tabs_1" role="tab" class="text-lg text-gray-950 border-0 rounded-none tab focus:!bg-transparent hover:!bg-transparent checked:bg-transparent focus:ring-0 focus:!border-emerald-800" aria-label="Mappa" checked="checked" />
                    <div role="tabpanel" class="py-8 space-y-6 tab-content">
                        <img src="https://italia.github.io/design-comuni-pagine-statiche/assets/images/map-placeholder.svg" alt="" class="block w-full">
                    </div>
                    <input type="radio" name="my_tabs_1" role="tab" class="text-lg text-gray-950 border-0 rounded-none tab focus:!bg-transparent hover:!bg-transparent checked:bg-transparent focus:ring-0 focus:!border-emerald-800" aria-label="Elenco" />
                    <div role="tabpanel" class="py-8 space-y-4 tab-content">
                        @foreach(['a', 'b', 'c'] as $item)
                        <x-filament::section>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold">Buca in via Solferino</h3>
                                <div class="space-y-2">
                                    <p>Tipologia di segnalazione</p>
                                    <p><strong>Verde pubblico e arredo urbano</strong></p>
                                </div>
                                <hr class="border-gray-300" />
                                <a href="" class="btn btn-neutral">
                                    <div class="text-white">Mostra tutto</div>
                                    <x-heroicon-o-chevron-down class="size-4" />
                                </a>
                            </div>
                        </x-filament::section>
                        @endforeach
                        <div class="py-4 text-center">
                            <button class="btn btn-outline text-emerald-600 btn-lg">Carica altre segnalazioni</button>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold lg:text-4xl">Fai una segnalazione</h2>
                    <p>Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE.</p>
                    <br />
                    <a href="" class="text-white btn btn-neutral">Segnala disservizio</a>
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
 @endvolt()