<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Bulletin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'tagline',
        'edition_date',
        'edition_time',
        'badge_text',
        'header_location',
        'section_1_title',
        'section_1_color',
        'section_2_title',
        'section_2_color',
        'footer_title',
        'footer_slogan',
        'footer_whatsapp',
        'footer_instagram',
        'footer_website',
        'footer_editorial_note',
        'theme_style',
        'status',
        'slug',
    ];

    protected $casts = [
        'edition_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($bulletin) {
            if (empty($bulletin->slug)) {
                $bulletin->slug = Str::slug($bulletin->title . '-' . ($bulletin->edition_date ? $bulletin->edition_date->format('Y-m-d') : now()->format('Y-m-d'))) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BulletinItem::class)->orderBy('section')->orderBy('position');
    }

    public function sectionOneItems(): HasMany
    {
        return $this->hasMany(BulletinItem::class)->where('section', 1)->orderBy('position');
    }

    public function sectionTwoItems(): HasMany
    {
        return $this->hasMany(BulletinItem::class)->where('section', 2)->orderBy('position');
    }

    /**
     * Formatted date in Brazilian Portuguese: "27 DE SETEMBRO DE 2026"
     */
    public function getFormattedDatePtAttribute(): string
    {
        if (!$this->edition_date) {
            return '';
        }
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];
        $carbon = Carbon::parse($this->edition_date);
        return $carbon->day . ' DE ' . ($months[$carbon->month] ?? '') . ' DE ' . $carbon->year;
    }

    /**
     * Formatted day of the week: "DOMINGO", "SEGUNDA-FEIRA", etc.
     */
    public function getDayOfWeekPtAttribute(): string
    {
        if (!$this->edition_date) {
            return '';
        }
        $days = [
            0 => 'DOMINGO',
            1 => 'SEGUNDA-FEIRA',
            2 => 'TERÇA-FEIRA',
            3 => 'QUARTA-FEIRA',
            4 => 'QUINTA-FEIRA',
            5 => 'SEXTA-FEIRA',
            6 => 'SÁBADO'
        ];
        return $days[Carbon::parse($this->edition_date)->dayOfWeek] ?? '';
    }
}
