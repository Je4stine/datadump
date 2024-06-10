<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleStat extends Model
{
    //
    protected $fillable = ['ip_address','country','referrer','post_website_id','article_id','points'];
    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function getLink(){
        $published = PublishedArticle::where([
            ['post_website_id','=',$this->post_website_id],
            ['article_id','=',$this->article_id]
        ])->first();
        if($published){
            return $published->link;
        }

    }
}
