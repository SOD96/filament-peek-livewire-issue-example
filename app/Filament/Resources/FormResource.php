<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;
use RalphJSmit\Filament\Components\Forms\Sidebar;
use RalphJSmit\Filament\Components\Forms\Timestamps;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Sidebar::make([
                Tabs::make('Forms')
                    ->tabs([
                        Tab::make('Details')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->helperText('This is the internal title of the form.')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                TextInput::make('heading')
                                    ->label('Heading')
                                    ->helperText('This is the heading that will be displayed above the form.')
                                    ->required()
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                TiptapEditor::make('intro')
                                    ->label('Intro')
                                    ->profile('digitonic')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ])
                                    ->extraInputAttributes(['style' => 'min-height: 24rem;']),

                            ]),

                        Tab::make('Fields')
                            ->icon('heroicon-o-bars-3-center-left')
                            ->schema([
                                Repeater::make('fields')
                                    ->collapsible()
                                    ->collapsed()
                                    ->minItems(1)
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                    ->schema([
                                        TextInput::make('label')
                                            ->label('Label')
                                            ->required(),

                                        TextInput::make('placeholder')
                                            ->label('Placeholder')
                                            ->nullable(),

                                        TextInput::make('helper_text')
                                            ->label('Helper Text')
                                            ->helperText('This text will be displayed below the field as a hint.')
                                            ->nullable(),

                                        TextInput::make('hint_text')
                                            ->label('Hint Text')
                                            ->helperText('This text will be displayed beside the label as a hint.')
                                            ->nullable(),

                                        Select::make('type')
                                            ->label('Type')
                                            ->live()
                                            ->options([
                                                'text' => 'Text',
                                                'email' => 'Email',
                                                'phone' => 'Phone',
                                                'textarea' => 'Textarea',
                                                'select' => 'Select',
                                            ])
                                            ->required(),

                                        Select::make('validation')
                                            ->multiple()
                                            ->label('Validation Rules')
                                            ->options([
                                                'required' => 'Required',
                                                'email:rfc,dns' => 'Valid Email',
                                                'numeric' => 'Numeric',
                                                'min:3' => 'Min Length 3',
                                                'max:255' => 'Max Length 255',
                                                'phone:AUTO' => 'Valid Phone Number',
                                            ])
                                            ->hint('Select multiple validation rules for this field.'),

                                        TextInput::make('options')
                                            ->label('Options')
                                            ->helperText('Comma separated list of options')
                                            ->visible(fn (Forms\Get $get) => $get('type') === 'select')
                                            ->columnSpan([
                                                'sm' => 2,
                                            ]),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Styles')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\ColorPicker::make('styles.background_color')
                                    ->label('Background Color')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('styles.enable_full_width')
                                    ->label('Full Width')
                                    ->default(false)
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('styles.enable_inline_labels')
                                    ->helperText('This will put labels on the same line as the input field.')
                                    ->label('Inline Labels')
                                    ->default(false)
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('styles.enable_centering')
                                    ->label('Center Form')
                                    ->default(false)
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('styles.hide_labels')
                                    ->label('Hide Labels')
                                    ->default(false)
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                TagsInput::make('settings.tags')
                                    ->label('Tags')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                TextInput::make('settings.cta_text')
                                    ->label('CTA Label')
                                    ->default('Submit')
                                    ->required()
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('settings.enable_privacy')
                                    ->label('Require Privacy Agreement')
                                    ->default(false)
                                    ->live()
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                TextInput::make('settings.privacy_policy_url')
                                    ->label('Privacy Policy URL')
                                    ->visible(fn (Forms\Get $get) => (bool) $get('settings.enable_privacy'))
                                    ->default('/privacy-policy')
                                    ->helperText('This will be used if you do not have a privacy policy statement.')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                MarkdownEditor::make('settings.privacy_policy_statement')
                                    ->label('Privacy Policy Statement')
                                    ->visible(fn (Forms\Get $get) => (bool) $get('settings.enable_privacy'))
                                    ->helperText('This statement will be used instead of the hardcoded copy using the URL above.')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                Forms\Components\Toggle::make('settings.enable_success_url')
                                    ->label('Enable Success URL')
                                    ->default(false)
                                    ->live()
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),

                                TiptapEditor::make('settings.success_message')
                                    ->label('Success Message')
                                    ->profile('digitonic')
                                    ->hidden(fn (Forms\Get $get) => (bool) $get('settings.enable_success_url'))
                                    ->helperText('This message will be displayed to the user after the form has been successfully submitted.')
                                    ->extraInputAttributes(['style' => 'min-height: 24rem;']),

                                TextInput::make('settings.success_url')
                                    ->label('Success URL')
                                    ->visible(fn (Forms\Get $get) => (bool) $get('settings.enable_success_url'))
                                    ->helperText('This is the location the user will be redirected to after the form has been successfully submitted.')
                                    ->url()
                                    ->nullable()
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),
                            ]),
                    ])
                    ->columnSpan(3),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
