<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class HomepageController extends Controller
{
    public function index (){
        $lastData = $this->lastData();
        
        $data = Post::where('status','publish')->where('id','!=',$lastData->id)->orderBy('id','desc')->paginate(2);
        return view('components.front.home-page',compact('data','lastData'));
    }

    private function lastData(){
        $data = Post::where('status','publish')->orderBy('id','DESC')->latest()->first();
        return $data;
    }
}
