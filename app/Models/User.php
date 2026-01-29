<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'is_approved',
        'is_active',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the employee record associated with this user
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            $name = trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
            return $name;
        }
        return $this->name ?? '';
    }

    /**
     * Check if user is an admin (you can customize this logic)
     */
    public function isAdmin(): bool
    {
        // Customize this based on your admin logic
        // For example, check a role or is_admin column
        return $this->is_approved && $this->status === 'active';
    }
}
