<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $table = 'cashes';

    protected $fillable  = [        
        'name',
        'amount',
        'description',
        'slug',
        'when'
    ];

    protected $dates=[
        'when'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
