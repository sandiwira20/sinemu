<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostReportItem extends Model
{
    use HasFactory;

    protected $fillable = ['lost_report_id', 'item_id', 'matched_at'];

    protected $casts = [
        'matched_at' => 'datetime',
    ];

    public function lostReport()
    {
        return $this->belongsTo(LostReport::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
