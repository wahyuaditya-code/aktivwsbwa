<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use App\Filament\Resources\BookingTransactionResource;
use App\Models\WorkshopParticipant;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateBookingTransaction extends CreateRecord
{
    protected static string $resource = BookingTransactionResource::class;
    
    protected function afterCreate():void
    {
        DB::transaction(function(){
            $record =$this->record;
            $participants=$this->form->getState()['participants'];

            //Iterate over each participant and create a record in the workshop_participants table
            foreach($participants as $participant){
                WorkshopParticipant::create([
                    'workshop_id' => $record->workshop_id,
                    'name' => $participant['name'],
                    'ocupation' => $participant['ocupation'],
                    'email' => $participant['email'],
                    'booking_transaction_id' => $record->id,
                ]);
            }
        });
    }
}
