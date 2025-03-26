<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'region' => Region::class,
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [

            Tabs::make()
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Conference details')
                        ->schema([
                            TextInput::make('name')
                                ->columnSpanFull()
                                ->label('Conference')
                                ->default('My Conference')
                                ->maxLength(60)
                                ->required(),
                            MarkdownEditor::make('description')
                                ->columnSpanFull()
                                ->required(),
                            DateTimePicker::make('start_date')
                                ->native(false)
                                ->required(),
                            DateTimePicker::make('end_date')
                                ->native(false)
                                ->required(),

                            Fieldset::make('Status')
                                ->columns(1)
                                ->schema([
                                    Select::make('status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'archived' => 'Archived',
                                        ])
                                        ->required(),
                                    Toggle::make('is_published')
                                        ->default(true),
                                ])
                        ]),
                    Tabs\Tab::make('Location')
                        ->schema([
                            Select::make('region')
                                ->live()
                                ->enum(Region::class)
                                ->options(Region::class),
                            Select::make('venue_id')
                                ->searchable()
                                ->preload()
                                ->createOptionForm(Venue::getForm())
                                ->editOptionForm(Venue::getForm())
                                ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                                    return $query->where('region', $get('region'));
                                }),
                        ]),
                ]),

//            Section::make('Conference Details')
//                ->collapsible()
//                ->description('Provide the details of the conference.')
//                ->icon('heroicon-o-information-circle')
//                ->columns(2)
//                ->schema([
//
//                ]),

//            Section::make('Location')
//                ->columns(2)
//                ->schema([
//                    Select::make('region')
//                        ->live()
//                        ->enum(Region::class)
//                        ->options(Region::class),
//                    Select::make('venue_id')
//                        ->searchable()
//                        ->preload()
//                        ->createOptionForm(Venue::getForm())
//                        ->editOptionForm(Venue::getForm())
//                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
//                            return $query->where('region', $get('region'));
//                        }),
//                ]),
        ];
    }
}
