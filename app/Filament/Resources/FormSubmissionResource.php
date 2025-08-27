<?php

namespace App\Filament\Resources;

use App\Filament\Exports\FormSubmissionExporter;
use App\Filament\Resources\FormSubmissionResource\Pages;
use App\Models\FormSubmission;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Novadaemon\FilamentPrettyJson\Form\PrettyJsonField;
use RalphJSmit\Filament\Components\Forms\Sidebar;
use RalphJSmit\Filament\Components\Forms\Timestamps;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Sidebar::make([
                Section::make()
                    ->schema([
                        PrettyJsonField::make('data'),
                    ]),
            ], [
                Section::make()
                    ->schema([
                        ...Timestamps::make(),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('form.title')
                    ->label('Form')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('data.heading')
                    ->label('Form Heading')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('data.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true, true),

                Tables\Columns\TextColumn::make('data.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('data.mobile')
                    ->label('Mobile')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true, true),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(true, true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(true, true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_id')
                    ->label('Form')
                    ->options(fn () => \App\Models\Form::pluck('title', 'id')->toArray())
                    ->searchable(),

                Filter::make('form_submitted_at')
                    ->form([
                        DatePicker::make('created_at')
                            ->native(false)
                            ->placeholder(fn ($state): string => 'Dec 18, '.now()->subYear()->format('Y')),
                        DatePicker::make('created_until')
                            ->native(false)
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->columnSpan(2),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(FormSubmissionExporter::class),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormSubmissions::route('/'),
            'create' => Pages\CreateFormSubmission::route('/create'),
            'edit' => Pages\EditFormSubmission::route('/{record}/edit'),
        ];
    }
}
