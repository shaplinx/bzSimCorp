<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Messaging\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Sanctum\PersonalAccessToken;
use Ladder\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $with = ["roles"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    public function getAllPermissionsAttribute()
    {
        return $this->permissions();
    }

    public function setRoles($newRoles = [])
    {
        $oldRoles = $this->roles->pluck("role");
        $newRoles = collect($newRoles)->unique()->each(function ($newRole) {
            $this->roles()->updateOrCreate(["role" => $newRole]);
        });
        $oldRoles->diff($newRoles)->each(function ($unused) {
            $this->roles()->where(["role" => $unused])->delete();
        });
    }

    public function sentMessages() {
        return $this->hasMany(Message::class,'sender_id');
    }

    public function getSentMessagesCountAttribute() {
        $this->sentMessages()->count();
    }

}
