<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TStruk extends Model
{
    use HasFactory;
    protected $table = 'tabel_struk';
    protected $guarded = [''];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
