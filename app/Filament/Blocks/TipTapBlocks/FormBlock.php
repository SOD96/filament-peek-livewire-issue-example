<?php

namespace App\Filament\Blocks\TipTapBlocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapBlock;

class FormBlock extends TiptapBlock
{
    public string $preview = 'filament.tiptapblocks.previews.form';

    public string $rendered = 'filament.tiptapblocks.rendered.form';

    /**
     * @return mixed[] The form schema.
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('custom_id')
                ->columnSpan(3)
                ->label('Custom ID'),

            Select::make('form')
                ->label('Select a Form')
                ->columnSpan(3)
                ->options(\App\Models\Form::pluck('title', 'id')->toArray()),
        ];
    }
}
