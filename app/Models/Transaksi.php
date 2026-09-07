<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $guarded = [''];
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;  

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
