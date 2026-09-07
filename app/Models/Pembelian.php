<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'stok_supplier';
    protected $guarded = [''];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
