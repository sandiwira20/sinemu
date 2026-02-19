<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'lost_report_id',
        'description',
        'contact',
        'claimed_at',
        'status',
        'verified_by',
        'verified_at',
        'evidence_file',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function lostReport()
    {
        return $this->belongsTo(LostReport::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function images()
    {
        return $this->hasMany(ClaimImage::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? ucfirst($this->status ?? '');
    }
}
