<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class .
 */
return new class extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->longText('description')->nullable();
                $table->foreignId('owner_id')->nullable(); // ->constrained('users');
                $table->foreignId('status_id')->constrained('project_statuses');
                $table->string('status_type')->default('default');
                $table->string('type')->default('kanban');
                $table->string('ticket_prefix')->unique();
                $table->softDeletes();
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if (! $this->hasColumn('ticket_prefix')) {
                    $table->string('ticket_prefix'); // ->unique();
                }

                // if ($this->hasColumn('ticket_prefix')) {
                //     $table->string('ticket_prefix')->unique()->change();
                // }
                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
