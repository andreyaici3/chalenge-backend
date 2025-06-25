<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ["car_name", "day_rate", "month_rate", "image"];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
