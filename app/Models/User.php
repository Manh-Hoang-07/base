<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id'
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
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function can($permission, $arguments = [])
    {
        // Nếu user có quyền trực tiếp, trả về true
        if (parent::can($permission, $arguments)) {
            return true;
        }

        // Kiểm tra nếu quyền này có quyền cha nhiều cấp
        $perm = Permission::where('name', $permission)->with('parent')->first();
        while ($perm && $perm->parent) {
            $perm = $perm->parent;
            if (parent::can($perm->name, $arguments)) {
                return true;
            }
        }

        return false;
    }

    public function canAny($permissions, $arguments = [])
    {
        foreach ($permissions as $permission) {
            if ($this->can($permission, $arguments)) {
                return true;
            }
        }
        return false;
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_user');
    }

}
