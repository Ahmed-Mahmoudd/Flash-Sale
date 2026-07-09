<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'hold_id',
        'product_id',
        'qty',
        'status',
    ];

    public function hold()
    {
        return $this->belongsTo(Hold::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function webhookEvents()
    {
        return $this->hasMany(WebhookEvent::class);
    }
}
