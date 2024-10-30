<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkshopParticipantResource\Pages;
use App\Filament\Resources\WorkshopParticipantResource\RelationManagers;
use App\Models\WorkshopParticipant;
use App\Models\Workshop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkshopParticipantResource extends Resource
{
    protected static ?string $model = WorkshopParticipant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('ocupation')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('email')
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('workshop_id')
                ->relationship('workshops','name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\Select::make('booking_transaction_id')
                ->relationship('bookingTransaction','booking_trx_id')
                ->preload()
                ->searchable()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('workshops.thumbnail'),
                
                Tables\Columns\TextColumn::make('name') 
                ->searchable(),
                Tables\Columns\TextColumn::make('bookingTransaction.booking_trx_id')
                ->searchable() ,

                Tables\Columns\TextColumn::make('email')
                ->searchable(),
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
            'index' => Pages\ListWorkshopParticipants::route('/'),
            'create' => Pages\CreateWorkshopParticipant::route('/create'),
            'edit' => Pages\EditWorkshopParticipant::route('/{record}/edit'),
        ];
    }
}
