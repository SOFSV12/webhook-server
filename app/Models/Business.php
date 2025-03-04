<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected  $fillable = [
        'id',
        'name',
        'hook_url',
        'api_key',
    ];

    protected $hidden = ['api_key'];

    public function jokes(): HasMany
    {
        return $this->hasMany(Joke::class);
    }

    public static function retreiveBusiness($apiKey)
    {
        $businesses = Business::all();

            $business = null;

            foreach ($businesses as $b) {
                if (Hash::check($apiKey, $b->api_key)) {
                    $business = $b;
                    return $business;
                    break;
                }
            }
            return null;
    }
}
