<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'created_by',
        'name',
        'description',
        'status',
        'found_location',
        'found_at',
        'stored_location',
        'contact',
        'main_image',
    ];

    protected $casts = [
        'found_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function lostReportItems()
    {
        return $this->hasMany(LostReportItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'stored' => 'Tersimpan',
            'processing' => 'Diproses',
            'returned' => 'Dikembalikan',
            'claimed' => 'Diklaim',
        ];

        return $labels[$this->status] ?? ucfirst($this->status ?? '');
    }
}
