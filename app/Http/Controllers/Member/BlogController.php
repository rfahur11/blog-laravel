<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user=Auth::user();
        $search=$request->search;
        
        $data = Post ::where('user_id',$user->id)->where(function($query) use ($search){
            if ($search) {
                $query->where('title','like',"%{$search}%")->orWhere('content','like',"%{$search}%");
            }
        })->orderBy('id','desc')->paginate(3)->withQueryString();
        return view('member.blogs.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('member.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
            'thumbnail'=>'image|mimes:jpeg,jpg,png|max:10240'
        ],[
            'title.required'=>'Masukkan judul',
            'content.required'=>'Masukkan konten',
            'thumbnail.image'=>'Please enter image',
            'thumbnail.mimes'=>'ekstensi yang dibolehkan hanya untuk JPEG,JPG dan PNG',
            'thumbnail.max'=>'Ukuran gambar tidak boleh lebih dari 10MB'
        ]
        );

        if ($request->hasFile('thumbnail')) {
            $destination_path = public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'));
        
            // Cek apakah direktori ada, jika tidak buat
            if (!file_exists($destination_path)) {
                mkdir($destination_path, 0755, true);
            }
        
            $image = $request->file('thumbnail');
            $image_name = time() . "_" . $image->getClientOriginalName();
            $image->move($destination_path, $image_name);
        }
        

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'draft', // Default status
            'description' => $request->description ?? '', // Default description
            'thumbnail' => isset($image_name) ? $image_name : ($post->thumbnail ?? null),
            'slug' => $this->generateSlug($request->title, $post->id ?? null),
            'user_id' => Auth::user()->id,
        ];
        

        Post::create($data);
        return redirect()->route('member.blogs.index')->with('success','Data Berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('edit',$post);
        $data=$post;
        return view('member.blogs.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
            'thumbnail'=>'image|mimes:jpeg,jpg,png|max:10240'
        ],[
            'title.required'=>'Masukkan judul',
            'content.required'=>'Masukkan konten',
            'thumbnail.image'=>'Please enter image',
            'thumbnail.mimes'=>'ekstensi yang dibolehkan hanya untuk JPEG,JPG dan PNG',
            'thumbnail.max'=>'Ukuran gambar tidak boleh lebih dari 10MB'
        ]
        );

        if ($request->hasFile('thumbnail')) {
            
            if (isset($post->thumbnail) && file_exists(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail)) {
                unlink(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail);
            }

            $image = $request->file('thumbnail');
            $image_name = time()."_".$image->getClientOriginalName();
            $destination_path=public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'));
            $image->move($destination_path,$image_name);
        }

        $data=[
            'title'=>$request->title,
            'content'=>$request->content,
            'status'=>$request->status,
            'description'=>$request->description,
            'thumbnail'=>isset($image_name)?$image_name:$post->thumbnail,
            'slug' =>$this->generateSlug($request->title,$post->id)
        ];

        Post::where('id',$post->id)->update($data);
        return redirect()->route('member.blogs.index')->with('success','Data Berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete',$post);
        if (isset($post->thumbnail) && file_exists(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail)) {
            unlink(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail);
        }
        Post::where('id',$post->id)->delete();
        return redirect()->route('member.blogs.index')->with('success','Data Berhasil dihapus ');

    }

    private function generateSlug($title, $id=null){
        $slug = Str::slug($title);
        $count = Post::where('slug',$slug)->when($id, function($query,$id){
            return $query->where('id','!=',$id);
        })->count();

        if ($count > 0) {
            $slug = $slug."-".($count + 1);
        }
        return $slug;
    }
}
