<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Namu\WireChat\Traits\Chatable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Chatable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable =
    [
        'email',
        'password',
        'role',
        'name',
        'id_card',
        'birthday',
        'nationality',
        'account_status',
        'gender',
        'personal_image',
        'contact_info',
        'size_info',
        'tshirt_size',
        'trousers_size',
        'shoes_size',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
    ];
    protected $casts = [
        'contact_info' => 'array',
        'size_info' => 'array',
        'birthday' => 'date',
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
            'password' => 'hashed',
        ];
    }
    public function getPersonalImageAttribute()
    {
        $path = $this->attributes['personal_image'] ?? null;

        if (!$path) {
            return asset('images/default-avatar.png');
        }

        // If already a full URL, just return it
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Check local public storage first
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        } catch (\Exception $e) {
            \Log::error('Error accessing public storage for image: ' . $e->getMessage());
        }

        // Then check S3 as fallback
        try {
            if (Storage::disk('s3')->exists($path)) {
                return Storage::disk('s3')->url($path);
            }
        } catch (\Exception $e) {
            \Log::error('Error accessing S3 storage for image: ' . $e->getMessage());
        }

        return asset('images/default-avatar.png');
    }
    public function getCoverUrlAttribute(): ?string
    {
        $path = $this->attributes['personal_image'] ?? null;

        if ($path) {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            try {
                if (\Storage::disk('public')->exists($path)) {
                    return \Storage::url($path);
                }
            } catch (\Exception $e) {}
            try {
                if (\Storage::disk('s3')->exists($path)) {
                    return \Storage::disk('s3')->url($path);
                }
            } catch (\Exception $e) {}
        }

        // No image — generate a colored SVG avatar with the first Arabic letter
        $name    = $this->attributes['name'] ?? 'م';
        $initial = mb_substr($name, 0, 1, 'UTF-8');

        $palette = ['#6d28d9','#dc2626','#2563eb','#059669','#d97706','#db2777','#0891b2','#65a30d','#7c3aed','#b45309'];
        $bg      = $palette[abs(crc32($name)) % count($palette)];

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
  <circle cx="50" cy="50" r="50" fill="{$bg}"/>
  <text x="50" y="50" font-family="Arial,Tahoma,sans-serif" font-size="46" font-weight="700"
        fill="white" text-anchor="middle" dominant-baseline="central">{$initial}</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function getGender()
    {
        return match ($this->gender) {
            'male' => 'ذكر',
            'female' => 'أنثى',
            default => 'غير محدد',
        };
    }


    public function getAge()
    {
        if (!$this->birthday) {
            return null;
        }

        return Carbon::parse($this->birthday)->age;
    }
    private function generateSaudiNumber($phone)
    {
        $phone = $this->contact_info['phone_number'] ?? '';
        $digits = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($digits, '05')) {
            $digits = '966' . substr($digits, 1);
        } elseif (Str::startsWith($digits, '5')) {
            $digits = '966' . $digits;
        } elseif (!Str::startsWith($digits, '966')) {
            $digits = '966' . ltrim($digits, '0');
        }

        return '+' . $digits;
    }

    public function generateWhatsappLink($phone)
    {
        $phone = $this->contact_info['phone_number'] ?? '';
        $cleanNumber = $this->generateSaudiNumber($phone);
        return 'https://wa.me/' . ltrim($cleanNumber, '+');
    }

    public function canCreateChats(): bool
    {
        return true;
    }
    public function canCreateGroups(): bool
    {
        return true;
    }
    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }


    public function receivesBroadcastNotificationsOn(): array
    {
        $channels = [
            'new-employee.' . $this->id,
            'employee-requests.' . $this->id,
            'employee-deductions.' . $this->id,
            'employee-alerts.' . $this->id,
            'employee-request-status.' . $this->id,
            'birthday.' . $this->id,
            'employee-login-ip.' . $this->id,
            'agreement.notice.' . $this->id,
            'client.request.sent.' . $this->id,
            'agreement.request.sent.' . $this->id,
            'new-agreement.' . $this->id,
            'agreement-renewed.' . $this->id,
            'pended-request.notice.' . $this->id,


        ];


        return $channels;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function can($ability, $arguments = [])
    {
        if (parent::can($ability, $arguments)) {
            return true;
        }
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $ability)) {
                return true;
            }
        }

        return false;
    }
}
