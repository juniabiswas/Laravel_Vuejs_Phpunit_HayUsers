<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttributes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'gender', 'date_of_birth',
    ];
    protected $primaryKey = 'id';

}
