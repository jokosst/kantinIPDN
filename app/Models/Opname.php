<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opname extends Model
{
    use HasFactory;
    protected $table = 'riwayat_stok_opname';
    protected $guarded = [''];
    protected $primaryKey = 'id_opname';
    public $timestamps = false; 
}
