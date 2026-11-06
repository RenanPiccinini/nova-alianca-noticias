<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulletinItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_id',
        'section',
        'position',
        'title',
        'description',
        'image_url',
        'source',
        'date_text',
        'source_url',
        'badge',
    ];

    public function bulletin(): BelongsTo
    {
        return $this->belongsTo(Bulletin::class);
    }
}
