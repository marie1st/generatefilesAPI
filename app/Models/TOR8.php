<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TOR8 extends Model
{
    use HasFactory;


             /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'D1',
        'D2',
        'D3',
        'D4',
        'D5',
        'departure1',
        'arrival1',
        'seat1',
        'date1',
        'name1',
        'nationality1',
        'age1',
        'D6',
        'D7',
        'D8',
        'D9',
        'passport1',
        'others1',
        'accom1',
        'list1',
        'F1',
        'F2',
        'F3',
        'F4',
        'F5',
        'F6',
        'F7',
        'F8',
        'F9',
        'G1',
        'others2',
        'passenger1',
        'officer1',
        'tor8_filePDF',
        'tor8_fileDOCX',
        'tor8_fileJPEG'
    

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
