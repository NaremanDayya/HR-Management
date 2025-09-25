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
        'personal_image',
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
        if (!empty($this->attributes['personal_image'])) {
            return Storage::disk('s3')->temporaryUrl($this->attributes['personal_image'], Carbon::now()->addMinutes(5) );
        }

        $name = $this->attributes['name'] ?? 'User';
        return 'https://avatar.oxro.io/avatar.svg?name=' . urlencode($name) . '&background=random';
    }
    public function getGender()
    {
        return match ($this->gender) {
            'male' => 'ذكر',
            'female' => 'أنثى',
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
        $phone = $this->contact_info['phone_number'];
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
        $phone = $this->contact_info['phone_number'];
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
