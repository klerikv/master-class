<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'craft_type_id',
        'instructor_id',
        'title',
        'description',
        'date',
        'time_slot',
        'max_participants',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /** @var array<string, string> */
    public const TIME_SLOTS = [
        '9-11' => '09:00 - 11:00',
        '11-13' => '11:00 - 13:00',
        '13-15' => '13:00 - 15:00',
        '15-17' => '15:00 - 17:00',
    ];

    // Получить читаемое время
    public function getTimeFormattedAttribute(): string
    {
        return self::TIME_SLOTS[$this->time_slot];
    }

    protected function dateFullFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                Carbon::setLocale('ru');

                return Carbon::parse($this->date)->translatedFormat('d F Y');
            }
        );
    }

    /**
     * @return BelongsTo<CraftType, MasterClass>
     */
    public function craftType(): BelongsTo
    {
        return $this->belongsTo(CraftType::class);
    }

    /**
     * @return BelongsTo<User, MasterClass>
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * @return HasMany<Booking>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function availableSeats(): int
    {
        return $this->max_participants - $this->bookings()->count();
    }

    public function isAvailable(): bool
    {
        return $this->availableSeats() > 0;
    }

    public static function getOccupiedTimeSlots(int $instructorId, string $date): array
    {
        return self::where('instructor_id', $instructorId)
            ->where('date', $date)
            ->pluck('time_slot')
            ->toArray();
    }

    public function isPast(): bool
    {
        $date = $this->date;
        $startHour = explode('-', $this->time_slot)[0];
        $masterClassDateTime = Carbon::parse($date)->setTime((int) $startHour, 0, 0);

        return $masterClassDateTime->isPast();
    }

    public function canBook(): bool
    {
        return ! $this->isPast() && $this->isAvailable();
    }
}
