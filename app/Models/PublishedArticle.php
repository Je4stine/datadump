<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublishedArticle extends Model
{
    //
    protected $fillable = ['article_id','post_id','post_website_id','link'];

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function website(){
        return $this->belongsTo(PostWebsite::class,'post_website_id');
    }
}
