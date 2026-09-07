<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogReturn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * Adjust this if your DB uses a different table name.
     */
    protected $table = 'log_return';
    protected $guarded = [''];

    /**
     * The attributes that are mass assignable.
     */
     protected $primaryKey = 'id';

    /**
     * If you don't use `created_at`/`updated_at`, set to false.
     */
    public $timestamps = false;
}
