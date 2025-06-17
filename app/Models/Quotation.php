<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_name',
        'client_email',
        'client_phone',
        'quotation_date',
        'products',
        'total_fonasa_price',
        'total_private_price',
    ];

    protected $casts = [
        'products' => 'array',
        'quotation_date' => 'datetime',
    ];
}
