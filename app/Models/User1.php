<?php

namespace Chatty\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 
        'email', 
        'password',
        'first_name',
        'last_name',
        'location'

    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getName()
    {
        if ($this->first_name && $this->last_name) {
                return "{ $this->first_name } {$this->last_name } ";
        }
    }

    public function getNameOrUsername($value='')
    {
        return      $this->getName() ?: $this->username;
    }
    public function getFirstNameOrUsername()
    {
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl()
    {
        return "https://www.gravatar.com/avatar/{{ md5($this->email) }}?d=mm&s=40 ";
    }



}
