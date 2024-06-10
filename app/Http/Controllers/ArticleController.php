<?php

namespace App\Http\Controllers;

use App\ArticleStat;
use App\PostWebsite;
use App\PublishedArticle;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Article;

use App\User;

use Auth;

use Illuminate\Support\Facades\Redirect;

use App\Repositories\MenuRepository;

class ArticleController extends Controller
{
    protected $user;
    protected $folder="articles.";
    public function __construct()
    {
        $this->user=Auth::user();
        // $role=new MenuRepository();
        // $role->check();

    }

    /**
     * Articles group by status:
     *  0->drafts
     *  1->pending
     *  2->approved
     * 3->disapproved
     */

    public function index()
    {
        $user_id=Auth::user()->id;
        $articles=Article::where('user_id',$user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->folder.'index',[
            'articles'=>$articles
        ]);
    }

    public function newArticle()
    {
        return view($this->folder.'new');
    }

    public function getDraft()
    {
         $user_id=Auth::user()->id;
        $draft_articles=Article::where('user_id',$user_id)
            ->where('status',0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->folder.'drafts',[
         'draft_articles'=>$draft_articles
        ]);
    }
    public function getPending()
    {
         $user_id=Auth::user()->id;
        $pending_articles=Article::where('user_id',$user_id)
            ->where('status',1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->folder.'pending',[
         'pending_articles'=>$pending_articles
        ]);
    }
    public function getApproved()
    {
         $user_id=Auth::user()->id;
        $approved_articles=Article::where('user_id',$user_id)
            ->where('status',2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->folder.'approved',[
         'approved_articles'=>$approved_articles
        ]);
    }
    public function getDisapproved()
    {
         $user_id=Auth::user()->id;
        $disapproved_articles=Article::where('user_id',$user_id)
            ->where('status',3)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->folder.'disapproved',[
         'disapproved_articles'=>$disapproved_articles
        ]);
    }

    public function submitArticle(Article $article)
    {
        if($article->user_id==$this->user->id){
            $article->status = 1;
            $article->update();

        }

        return redirect()->back()->with('notice',['class'=>'success','message'=>'article submited']);
    }

    public function editArticle(Article $article)
    {
        if(Auth::user()->role == 'writer')
            return redirect('writer?tab=edit&id='.$article->id);
        return redirect('stud?tab=edit&id='.$article->id);
    }

    public function storeDraft(Request $request)
    {
        $status=$request->status;
        $id=$request->id;
        if ($id == 0) {
            $this->user->articles()->updateOrCreate(['id' => $request->id], [
                'title' => $request->title,
                'content' => $request->article_content,
                'status' => 0,
            ]);
        }else{
            $this->user->articles()->updateOrCreate(['id' => $request->id], [
                'title' => $request->title,
                'content' => $request->article_content,
                'status' => $request->status,
                ]);
        }

        if ($status==0){
            if(Auth::user()->role == 'writer')
                 return Redirect::to('writer?tab=drafts')->with('notice',['class'=>'success','message'=>'article saved']);

            return Redirect::to('stud?tab=drafts')->with('notice',['class'=>'success','message'=>'article saved']);
        }
        else{
             if(Auth::user()->role == 'writer')
                            return Redirect::to('writer?tab=pending')->with('notice',['class'=>'success','message'=>'article saved']);

            return Redirect::to('stud?tab=pending')->with('notice',['class'=>'success','message'=>'article saved']);
        }

    }

    /**
     * Destroy the given article.
     * @param  Article  $article
     * @return Response
     */
    public function destroy(Article $article)
    {
        dd($article);
//        $article->delete();
        return redirect()->back()->with('notice',['class'=>'success','message'=>'article deleted']);
    }
    /*
     * set status to 2
     *
     */
    public function approve(Article $article)
    {
        $article->status=2;
        $article->update();
        return redirect()->back()->with('notice',['class'=>'success','message'=>'article disapproved']);
    }

    /*
     * set status to 3
     */
    public function disapprove(Article $article)
    {
     $article->status=3;
        $article->update();
        return redirect()->back()->with('notice',['class'=>'success','message'=>'article disapproved']);
    }

    public function processAnalytics(Request $request){
        $ip = $request->ip_address;
        $referrer  = $request->referrer;
        $post_id = $request->post_id;
        $web_url = $request->home_url;
        $web_url = str_replace('http://','',$web_url);
        $web_url = str_replace('https://','',$web_url);
        $post_website = PostWebsite::where([
            ['name','like',"%$web_url%"]
        ])->first();
        $published  = PublishedArticle::where('post_id',$post_id)->first();
        if($published){
            $article = $published->article;
            $user_website = $article->user->website;
            $author_points = $user_website->getAuthorPoints();
            $artile_id = $article->id;
            $data = [
                'ip_address'=>$ip,
                'referrer'=>$referrer,
                'article_id'=>$artile_id,
                'post_website_id'=>$post_website->id,
                'country'=>$this->getCountry($ip),
                'points'=>$author_points
            ];
            $exisiting_stat = ArticleStat::where([
                ['ip_address','=',$ip],
                ['post_website_id','=',$post_website->id],
                ['article_id','=',$artile_id]
            ])->first();
            if(!$exisiting_stat){
                $exisiting_stat =  ArticleStat::create($data);
            }
            return $exisiting_stat;
        }else{
            return [];
        }
    }

    public function getCountry($ip){
        //        var_dump($url);
        $url = "http://www.geoplugin.net/json.gp?ip=$ip";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, null);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));
        $content = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $json_response = null;
        if($status==200 || $status==201) {
            $json_response = json_decode($content);
            $country = $json_response->geoplugin_countryName;
            return $country;
        }else{
            return '';
        }
    }
}
