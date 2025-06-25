<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ["order_date", "pickup_date", "dropoff_date", "pickup_location", "dropoff_location", "car_id"];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
