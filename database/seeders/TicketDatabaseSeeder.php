<?php

declare(strict_types=1);

namespace Modules\Ticket\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
