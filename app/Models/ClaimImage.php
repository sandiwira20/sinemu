<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimImage extends Model
{
    use HasFactory;

    protected $fillable = ['claim_id', 'path'];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
