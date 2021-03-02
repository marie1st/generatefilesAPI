<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Covid extends Model
{
    use HasFactory;

           /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'date1',
        'name1',
        'license_no',
        'name2',
        'date2',
        'name3',
        'date3',
        'name4',
        'name5',
        'address1',
        'covid_filePDF',
        'covid_fileDOCX',
        'covid_fileJPEG',

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
