<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogHapus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'log_hapus';
    protected $guarded = [''];

    /**
     * The attributes that are mass assignable.
     */
     protected $primaryKey = 'id_log';

    public $timestamps = false;
}
