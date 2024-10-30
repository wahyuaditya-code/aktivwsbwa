<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class BookingTransaction extends Model
{
    use HasFactory,softDeletes;

    protected $fillable =[
        'name',
        'phone',
        'email',
        'customer_bank_name',
        'customer_bank_account',
        'booking_trx_id',
        'proof',
        'quantity',
        'is_paid',
        'workshop_id',
        'total_amount',
        'customer_bank_number',

    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'AKTIVBWA';
        do{
            $randomString = $prefix . mt_rand(1000, 9999);
        }while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function participants(): HasMany{
        return $this->hasMany(WorkshopParticipant::class);
    }

    public function workshop():BelongsTo{
        return $this->belongsTo(Workshop::class,'workshop_id');
    }

}
