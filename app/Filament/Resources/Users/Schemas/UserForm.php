<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('email')
                        ->email()
                        ->label('Email address')
                        ->required()
                        ->maxLength(100),    
                    TextInput::make('password')
                        ->password()
                        // ->required(fn (Forms\Form $form): bool => $form->getLivewire() instanceof Pages\CreateUser)
                        ->minLength(6)
                        ->same('password_confirmation')
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->label('Password Confirmation')
                        // ->required(fn (Forms\Form $form): bool => $form->getLivewire() instanceof Pages\CreateUser) // ✅ Perbaikan
                        ->minLength(6)
                        ->minlength(6)
                        ->dehydrated(false),
                    Select::make('user_group')
                        ->options([
                            'admin' => 'admin',
                            'pegawai' => 'pegawai',
                        ])
                        ->default('pegawai')

            ]);
    }
}
