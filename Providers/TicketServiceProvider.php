<?php

namespace Modules\Ticket\Providers;

use Illuminate\Support\ServiceProvider;

class TicketServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Rimuoviamo la registrazione manuale del componente
        // Il componente verrà autoregistrato da XotBaseServiceProvider
    }
} 