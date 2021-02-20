<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fit extends Model
{
    use HasFactory;

            /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
  
        'name1',
        'hnumber',
        'bday',
        'date1',
        'agerange',
        'roomno',
        'gendertype',
        'name2',
        'date2',
        'timerange',
        'name3',
        'D1',
        'D2',
        'D3',
        'D4',
        'D5',
        'D6',
        'E1',
        'E2',
        'E3',
        'E4',
        'E5',
        'E6',
        'E7',
        'E8',
        'E9',
        'name4',
        'licensem',
        'phone1',
        'name5',
        'name6',
        'date3',
        'name6',
        'passport_no',
        'relationship1',
        'language1',
        'witness1',
        'witness2',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
