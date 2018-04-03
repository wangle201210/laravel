<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

class User extends Authenticatable implements AuthenticatableUserContract {
    use LaratrustUserTrait, Notifiable, FilterAndSorting;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey(); // Eloquent model method
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() {
        return [
            'exp' => time() + 60 * 60 * 3,
            'user' => [
                'id' => $this->id,
            ],
        ];
    }
    /**
     * This scope allows to retrive the users with a specific role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRoleIs($query, $role = '') {
        return $query->whereHas('roles', function ($roleQuery) use ($role) {
            $roleQuery->where('name', $role);
        });
    }
    /**
     * This scope allows to retrive the users with some role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRoleIn($query, $role = []) {
        return $query->whereHas('roles', function ($roleQuery) use ($role) {
            $roleQuery->whereIn('name', $role);
        });
    }
}
