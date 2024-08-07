<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;

class TicketListBlock
{
    public static function make(
        string $name = 'ticket_list',
        string $context = 'form',
    ): Block {
        $view = 'ticket::components.blocks.ticket_list.card';
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        return Block::make($name)
            ->schema([
                // Select::make('article_id')
                //    ->label('Article')
                //    ->options(Article::published()->orderBy('title')->pluck('title', 'id'))
                //    ->required(),
                // TextInput::make('text')
                //    ->label('Link text (optional)'),
                TextInput::make('title')
                    ->label('Titolo')
                    ->helperText('Inserisci un titolo del blocco')
                    ->required(),
                TextInput::make('sub_title')
                    ->label('Sotto Titolo')
                    ->helperText('Inserisci un sotto titolo '),
                TextInput::make('method')
                        ->label('$_theme->{$method}')
                        ->hint('Inserisci il nome del metodo da richiamare nel tema')
                        ->required(),
                /*
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'latest' => 'latest',
                        'featured' => 'featured',
                    ])
                    ->required(),
                */
                TextInput::make('limit'),
                Select::make('_tpl')
                    ->label('layout')
                    ->options($views)
                    ->default('v1')
                    ->required(),
            ])
            ->label('Lista Ticket')
            ->columns('form' === $context ? 3 : 1);
    }
}
