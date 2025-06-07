<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
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
        'email',
        'password',
        'role',
        'google_id',
        'status'
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

    public function can($permission, $arguments = []): bool
    {
        try {
            // Nếu user có quyền trực tiếp, trả về true
            if (parent::can($permission, $arguments)) {
                return true;
            }

            // Kiểm tra nếu quyền này có quyền cha nhiều cấp
            $perm = Permission::where('name', $permission)->with('parent')->first();
            $maxDepth = 5; // Giới hạn độ sâu để tránh vòng lặp vô hạn
            $currentDepth = 0;

            while ($perm && $perm->parent && $currentDepth < $maxDepth) {
                $perm = $perm->parent;
                if (parent::can($perm->name, $arguments)) {
                    return true;
                }
                $currentDepth++;
            }

            return false;
        } catch (\Exception $e) {
            // Log lỗi và trả về false để tránh timeout
            Log::error('Permission check error: ' . $e->getMessage());
            return false;
        }
    }

    public function canAny($permissions, $arguments = []): bool
    {
        try {
            // Nếu không có permissions hoặc rỗng, trả về false
            if (empty($permissions)) {
                return false;
            }

            foreach ($permissions as $permission) {
                if ($this->can($permission, $arguments)) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            // Log lỗi và trả về false để tránh timeout
            Log::error('CanAny permission check error: ' . $e->getMessage());
            return false;
        }
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_users');
    }

}
