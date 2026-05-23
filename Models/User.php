<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'avatar',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship with Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Role checking helpers
     */
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isKasir()
    {
        return $this->role && $this->role->name === 'kasir';
    }

    public function isPembeli()
    {
        return $this->role && $this->role->name === 'pembeli';
    }

    public function hasRole($roles)
    {
        if (!$this->role) return false;
        
        $rolesArray = is_array($roles) ? $roles : explode(',', $roles);
        return in_array($this->role->name, array_map('trim', $rolesArray));
    }
}
