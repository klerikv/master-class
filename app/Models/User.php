<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'role',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function phoneFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $phone = $this->phone;
                if (preg_match('/^\+7(\d{3})(\d{3})(\d{2})(\d{2})$/', $phone, $matches)) {
                    return '+7 ('.$matches[1].') '.$matches[2].'-'.$matches[3].'-'.$matches[4];
                }

                return $phone;
            }
        );
    }

    /**
     * Summary of instructorMasterClasses
     *
     * @return HasMany<MasterClass>
     */
    public function instructorMasterClasses(): HasMany
    {
        return $this->hasMany(MasterClass::class, 'instructor_id');
    }

    /**
     * Summary of bookings
     *
     * @return HasMany<Booking>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isVisitor(): bool
    {
        return $this->role === 'visitor';
    }
}
