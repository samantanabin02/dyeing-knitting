<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Sitesetting extends Authenticatable
{
    use Notifiable;  

    protected $table = 'site_settings';
}
