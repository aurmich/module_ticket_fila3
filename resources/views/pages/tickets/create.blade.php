<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

name('ticket.create');
middleware(['auth']);

// new class extends Component
// {
// };
?>

<x-layouts.marketing>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600&display=swap" rel="stylesheet">
<style>
  .fi-input-wrp {
        ring: none !important;
        box-shadow: none !important;
        border-bottom: solid 1px !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        font-family: 'Titillium Web', sans-serif !important;
        font-weight: 400 !important;
        color: '#5d7083';
    }
</style>
    <div class="w-full">

        <x-ui.marketing.breadcrumbs :crumbs="[['text' => 'Tickets'],['text' => 'Create']]" />
        <div class="w-full">

            <br/>
            @livewire(\Modules\Ticket\Filament\Widgets\CreateTicketWidget::class)

        </div>
    </div>
    
</x-layouts.marketing>
