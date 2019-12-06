<?php

namespace Okipa\LaravelBootstrapComponents\Test\Models;

use Illuminate\Database\Eloquent\Model;

class UserMultilingual extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_test';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['name' => 'json'];
}