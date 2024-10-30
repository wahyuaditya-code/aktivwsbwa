<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WorkshopParticipant extends Model
{
    use HasFactory, softDeletes;

    protected $table='workshop_participants'; //takut ga kebaca 
    protected $fillable=[
        'name',
        'ocupation',
        'email',
        'booking_transaction_id',
        'workshop_id',

    ];
    public function workshops(): BelongsTo{
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }
    public function bookingTransaction(): BelongsTo{
        return $this->belongsTo(bookingTransaction::class, 'booking_transaction_id');
    }
}
