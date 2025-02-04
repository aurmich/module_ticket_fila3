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

        .title {
            font-family: 'Titillium Web', sans-serif !important;
            font-weight: 800 !important;
        }

        .fi-fo-rich-editor {
            border: none !important;
            box-shadow: none !important;
            --tw-ring-shadow: none !important;
            --tw-ring-color: transparent !important;
            background: transparent !important;
            padding: 0 !important;
            color: black !important;
            font-weight: 400 !important;
        }

        .fi-fo-rich-editor.fi-disabled {
            background: transparent !important;
            --tw-ring-shadow: none !important;
            --tw-ring-color: transparent !important;
            color: black !important;
            font-weight: 400 !important;
        }

        /* New styles for the form */
        .fi-fo-wizard {
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        .fi-fo-section {
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        .fi-fo-textarea {
            border-radius: 0 !important;
            box-shadow: none !important;
            border-bottom: solid 1px !important;
        }

        .fi-fo-select {
            border-radius: 0 !important;
            box-shadow: none !important;
            border-bottom: solid 1px !important;
        }
    </style>
    <div class="container mx-auto px-4 max-w-5xl">
        <x-ui.marketing.breadcrumbs :crumbs="[['text' => 'Tickets'],['text' => 'Create']]" />
        <div class="w-full">
            <h1 class="text-5xl mt-5 mb-2 title">Segnalazione disservizio</h1>
            <br />
            @livewire(\Modules\Ticket\Filament\Widgets\CreateTicketWidget::class)
        </div>
    </div>
</x-layouts.marketing>