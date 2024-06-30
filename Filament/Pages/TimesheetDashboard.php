<?php

namespace Modules\Ticket\Filament\Pages;

use Modules\Ticket\Filament\Widgets\Timesheet\ActivitiesReport;
use Modules\Ticket\Filament\Widgets\Timesheet\MonthlyReport;
use Modules\Ticket\Filament\Widgets\Timesheet\WeeklyReport;
use Filament\Pages\Page;

class TimesheetDashboard extends Page
{
    protected static ?string $slug = 'timesheet-dashboard';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament::pages.dashboard';

    protected function getColumns(): int | array
    {
        return 6;
    }

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Timesheet');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('View timesheet dashboard');
    }

    protected function getWidgets(): array
    {
        return [
            MonthlyReport::class,
            ActivitiesReport::class,
            WeeklyReport::class
        ];
    }
}
