<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllCards extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'company_id',
        'organization',
        'employee',
        'location',
        'hazard_description',
        'risked_resource',
        'probability',
        'impact',
        'existing_control',
        'existing_prevention',
        'rating',
        'other_info',
        'media'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

}
