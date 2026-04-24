<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'password',
        'divisi_id',
        'jabatan_id',
        'atasan_id',
        'status',
        'role',
        'must_change_password',
        'last_login',
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
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    /**
     * Check if user is super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->hasRole('Superadmin');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a standard user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function menuPermissions()
    {
        return $this->hasMany(MenuUserPermission::class);
    }

    public function canViewMenu($menuName)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Cache the permissions per user to avoid query duplication during sidebar render
        $permissions = \Illuminate\Support\Facades\Cache::remember(
            'user_menus_' . $this->id,
            3600,
            function () {
                return $this->menuPermissions()->with('menu')->get();
            }
        );

        $perm = $permissions->first(function ($p) use ($menuName) {
            return $p->menu && $p->menu->name === $menuName;
        });

        return $perm && $perm->can_view;
    }
}
