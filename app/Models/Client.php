<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mobile_number',
        'email_address',
        // Add other fields as needed
    ];
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
