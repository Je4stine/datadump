<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['content','title','user_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statistics(){
        return $this->hasMany(ArticleStat::class);
    }

    public function isApproved(){
        return $this->status == 2;
    }

     public function isPending(){
        return $this->status == 1;
    }

    public function isDraft(){
        return $this->status == 0;
    }

    public function approve(){
        $this->status = 2;
        return $this->update();
    }

    public function publishes(){
        return $this->hasMany(PublishedArticle::class);
    }
}
