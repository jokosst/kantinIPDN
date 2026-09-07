<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TStrukSementara extends Model
{
    use HasFactory;
    protected $table = 'tabel_struk_sementara';
    protected $guarded = [''];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
