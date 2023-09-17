<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id', // Foreign key referencing the Client model
        'amount',
        'due_date',
        // Add other fields as needed
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
