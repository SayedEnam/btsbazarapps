<?php

namespace App\Models;

use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
        'status',
        'referral_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    /**
     * The roles assigned to this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Determine if the user has the given role (by slug).
     */
    public function hasRole(string $slug): bool
    {
        return $this->roles->contains('slug', $slug);
    }

    /**
     * Determine if the user has any of the given roles (by slug).
     *
     * @param  array<int, string>  $slugs
     */
    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles->pluck('slug')->intersect($slugs)->isNotEmpty();
    }

    /**
     * Determine if the user has the given permission through any of their roles.
     */
    public function hasPermission(string $slug): bool
    {
        return $this->roles->loadMissing('permissions')
            ->pluck('permissions')
            ->flatten()
            ->contains('slug', $slug);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    /**
     * The customer profile for this user, when the user registered as a
     * customer (Phase 3). A user with the Marketing Officer role instead
     * has no customer profile — see referredCustomers() below.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Customers this user (as a Marketing Officer) has referred.
     */
    public function referredCustomers(): HasMany
    {
        return $this->hasMany(Referral::class, 'officer_id');
    }

    /**
     * The officer profile for this user, when the user has the Marketing
     * Officer role and an Officer profile has been created for them (Phase 4).
     */
    public function officer(): HasOne
    {
        return $this->hasOne(Officer::class);
    }

    /**
     * Generate the next sequential referral code (0001, 0002, ...) — no
     * "REF-" prefix, per the client's request to remove it everywhere.
     * Locks existing rows (including soft-deleted ones, so a removed
     * officer's code is never reissued and collided with) for the duration
     * of the transaction so two concurrent calls can't compute the same
     * next number. The str_replace stays so any legacy "REF-####" codes
     * already in the database (from before this change) still parse
     * correctly into the max computation — it's a no-op on codes that
     * never had the prefix.
     */
    public static function generateReferralCode(): string
    {
        return DB::transaction(function () {
            $max = static::withTrashed()
                ->whereNotNull('referral_code')
                ->lockForUpdate()
                ->pluck('referral_code')
                ->map(fn (string $code) => (int) str_replace('REF-', '', $code))
                ->max() ?? 0;

            return sprintf('%04d', $max + 1);
        });
    }
}
