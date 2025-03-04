<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Joke extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'name', 'joke'];

    public function business():BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
