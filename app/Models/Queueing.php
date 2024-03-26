<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queueing extends Model
{
    use HasFactory;

    protected $table = 'manange_queue';

    // Specify the fillable fields
    protected $fillable = ['queue_no', 'timestamp', 'transaction_id', 'status'];

    // Disable timestamps
    public $timestamps = false;

    protected $dates = [
        'timestamp',
    ];
}
