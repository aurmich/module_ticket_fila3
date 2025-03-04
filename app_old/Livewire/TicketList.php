<?php

namespace Modules\Ticket\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Volt\Component as VoltComponent;
use Modules\Ticket\Models\Ticket;
use Modules\Category\Models\Category;

class TicketList extends VoltComponent
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedStatus = '';
    public array $categories = [
        "Acqua, allagamenti, problemi fognari (21)",
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
    ];

    public function getTicketsProperty()
    {
        return Ticket::query()
            ->when($this->search, fn($q) => 
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%")
            )
            ->when($this->selectedCategory, fn($q) => 
                $q->whereHas('category', fn($q) => 
                    $q->where('name', $this->selectedCategory)
                )
            )
            ->when($this->selectedStatus, fn($q) => 
                $q->where('status', $this->selectedStatus)
            )
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('ticket::components.blocks.ticket_list.agid', [
            'tickets' => $this->tickets
        ]);
    }
} 