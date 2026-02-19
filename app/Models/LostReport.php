<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_type',
        'category_id',
        'item_name',
        'description',
        'lost_location',
        'contact',
        'evidence_file',
        'lost_at',
        'status',
    ];

    protected $casts = [
        'lost_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(LostReportItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'open' => 'Terbuka',
            'matched' => 'Cocok',
            'closed' => 'Ditutup',
        ];

        return $labels[$this->status] ?? ucfirst($this->status ?? '');
    }
}
