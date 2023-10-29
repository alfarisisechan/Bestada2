<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $table = 'blocks';
    protected $primaryKey = 'block_code';
    public $timestamps = false;
    
    protected $fillable = [
        'block_code',
        'block_max',
        'block_status',
    ];
}
