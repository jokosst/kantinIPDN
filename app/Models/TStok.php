<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TStok extends Model
{
    use HasFactory;
    protected $table = 'beli_stok';
    protected $guarded = [''];
    protected $primaryKey = 'id';
    public $timestamps = false;  
}
