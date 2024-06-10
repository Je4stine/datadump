<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = [
            'other_company',
            'style_ids',
            'cv_file_id',
            'subject_ids',
            'native_language',
            'academic_id',
            'about',
            'cert_title',
            'cert_file_id',
            'sample_essays',
            'payment_terms',
            'step'
        ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function language(){
        return $this->hasOne(Language::class);
    }

    public function academic(){
        return $this->belongsTo(Academic::class);
    }
}
