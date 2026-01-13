<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'category_id', 
        'manager_id', 
        'specifications', 
        'state'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}