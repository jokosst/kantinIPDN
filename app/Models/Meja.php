<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meja extends Model
{
    use HasFactory;
    protected $table = 'meja';
    protected $guarded = [''];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_unik)) {
                $model->kode_unik = Str::random(32); // generate unik 32 karakter
            }
        });
    }
}
