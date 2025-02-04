<?php

/**
 * @see https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component#adding-the-form
 */

declare(strict_types=1);

namespace Modules\Ticket\Filament\Widgets;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget as BaseWidget;
use Modules\Ticket\Events\TicketCreatedEvent;
use Modules\Ticket\Filament\Resources\TicketResource;
use Modules\Ticket\Models\Ticket;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

/**
 * @property ComponentContainer $form
 */
class CreateTicketWidget extends BaseWidget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'ticket::filament.widgets.create-ticket';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('step-1')
                    ->label('Autorizzazioni e condizioni')
                    ->schema([
                        RichEditor::make('privacy_notice')
                            ->label('')
                            ->default("Il Comune di Firenze gestisce i dati personali forniti e liberamente comunicati sulla base dell'articolo 13 del Regolamento (UE) 2016/679 General data protection regulation (Gdpr) e degli articoli 13 e successive modifiche e integrazione del decreto legislativo (di seguito d.lgs) 267/2000 (Testo unico enti locali).<br><br>Per i dettagli sul trattamento dei dati personali consulta l'<a href='/privacy' target='_blank' style='color: #007a52'>informativa sulla privacy</a>.")
                            ->disabled()
                            ->extraAttributes(['class' => 'border-0 shadow-none !p-0 !bg-transparent']),

                        Checkbox::make('accept_terms')
                            ->label("Ho letto e compreso l'informativa sulla privacy")
                            ->required()
                            ->default(false)
                            ->extraAttributes(['class' => 'text-green-500 text-lg checked:bg-green-500 checked:hover:bg-green-500 focus:ring-green-500'])
                            ->rules(['accepted']),
                    ])
                    ->afterValidation(function($state) {
                        if (!$state['accept_terms']) {
                            $this->addError('data.accept_terms', "Devi accettare l'informativa sulla privacy per continuare.");
                            $this->halt();
                        }
                    }),

                Step::make('step-2')
                    ->label('Dati di segnalazione')
                    ->schema([
                        Placeholder::make('')
                            ->content(new HtmlString('<h1 class="subtitle text-4xl font-bold mb-4">Disservizio*</h1>')),
                        ...TicketResource::getFormSchema(),
                    ]),
            ])
                ->nextAction(
                    fn (Action $action) => $action
                        ->label('Avanti')
                        ->size('xl')
                        ->extraAttributes(['class' => 'px-8 py-4 text-lg font-bold w-96 -ml-6'])
                )
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <button
                        type="submit"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-size-md gap-1.5 px-8 py-4 text-md font-bold w-64 bg-white text-green-600 border-2 border-green-600 hover:bg-green-50"
                    >
                        Salva richiesta
                    </button>
                BLADE)))
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'w-full max-w-full mx-auto',
                    'navigationContainerAttributes' => 'class="justify-start"'
                ]),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function create(): void
    {
        $ticket = Ticket::create($this->form->getState());
        TicketCreatedEvent::dispatch($ticket);
        redirect('/');
    }
}
