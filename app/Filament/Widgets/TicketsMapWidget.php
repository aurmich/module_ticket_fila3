<?php

namespace Modules\Ticket\Filament\Widgets;

use Cheesegrits\FilamentGoogleMaps\Widgets\MapWidget;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Enums\TicketStatusEnum;
use Illuminate\Support\Facades\Log;
use Filament\Support\RawJs;
use Livewire\Attributes\Reactive;

class TicketsMapWidget extends MapWidget
{
    #[Reactive]
    public array $categoryFilter = [];

    protected static ?string $heading = '';

    protected static ?int $sort = 1;

    protected static ?bool $clustering = true;

    protected static ?bool $fitToBounds = true;

    protected static ?string $mapId = 'incidents';

    protected static ?string $pollingInterval = null; // Disable polling

    public function getMapConfig(): string
    {
        $config = json_decode(parent::getMapConfig(), true);

        $config['center'] = [
            'lat' => 34.730369,
            'lng' => -86.586104,
        ];

        return json_encode($config);
    }

    public function rerender()
    {
        $this->dispatch('rerender-map');
    }

    protected function getData(): array
    {
        Log::error('Getting map data with filters', ['categories' => $this->categoryFilter]);

        $query = Ticket::query();

        // Apply category filter if any categories are selected
        if (!empty($this->categoryFilter)) {
            $query->whereIn('type', $this->categoryFilter);
        }

        // Then apply the status and user conditions
        $query->where(function ($q) {
            $q->whereIn('status', TicketStatusEnum::canViewByAll())
                ->orWhere('created_by', authId())
                ->orWhere('updated_by', authId());
        });

        $locations = $query->latest()->get();
        
        Log::error('Filtered locations', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'count' => $locations->count()
        ]);

        $data = [];

        foreach ($locations as $location) {
            if ($location->latitude && $location->longitude) {
                $data[] = [
                    'location' => [
                        'lat' => floatval($location->latitude),
                        'lng' => floatval($location->longitude),
                    ],
                    'label' => $location->name,
                    'id' => $location->id,
                    'icon' => $location->getIconData(),
                ];
            }
        }

        return $data;
    }

    protected function getMapOptions(): array
    {
        return [
            ...parent::getMapOptions(),
            'zoom' => 12,
            'zoomControl' => true,
            'mapTypeControl' => true,
            'scaleControl' => true,
            'streetViewControl' => true,
            'rotateControl' => true,
            'fullscreenControl' => true,
            'gestureHandling' => 'greedy',
        ];
    }

    public function getListeners()
    {
        return array_merge(parent::getListeners(), [
            'categoryFilterUpdated' => 'rerender',
        ]);
    }

    public function mount()
    {
        parent::mount();
        Log::error('Widget mounted with filters', ['categories' => $this->categoryFilter]);
    }
} 