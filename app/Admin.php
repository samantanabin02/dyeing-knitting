<?php
namespace App;

use App\Notifications\AdminResetPasswordNotification;

//Trait for sending notifications in laravel
use Illuminate\Foundation\Auth\User as Authenticatable;

//Notification for Admin
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{

    // This trait has notify() method defined
    use Notifiable;

    //Mass assignable attributes
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //hidden attributes
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
