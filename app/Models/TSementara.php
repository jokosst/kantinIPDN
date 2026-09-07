<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSementara extends Model
{
    use HasFactory;
    protected $table = 'transaksi_sementara';
    protected $guarded = [''];
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;
}
