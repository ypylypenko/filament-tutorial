<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            TextInput::make('email')
                ->email()
                ->maxLength(255)
                ->required(),
            Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            TextInput::make('twitter_handle')
                ->required(),
            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options([
                    'business-leader' => 'Business Leader',
                    'first-time' => 'First Time Speaker',
                    'twitter-influencer' => 'Twitter Influencer',
                    'youtube-influencer' => 'Youtube Influencer',
                    'open-source' => 'Open Source Creator / Contributor',
                ])
                ->descriptions([
                    'business-leader' => 'Some fancy description',
                    'first-time' => 'Some fancy description',
                    'twitter-influencer' => 'Some fancy description',
                ])
                ->columns(3)
                ->required(),
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }
}
