<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function avatarUrl()
    {
        if($this->photo) {
            return Storage::disk('s3-public')->url($this->photo);
        }
        return 'https://avatars.dicebear.com/api/initials/' . $this->name . '.svg';
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%')
                ->orWhere('email', 'like', '%'.$query.'%');
    }

    public function applicationUrl()
    {
        if($application = $this->application()){
            return url('/documents/' . $this->id . '/' . $application->filename);
        }
        return "#";
    }

    public function application()
    {
        return $document = $this->documents()->whereType('application')->first();
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
