<?php

namespace Database\Seeders;

use App\Models\Post;
use illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $judul = [
            'Selalu dipuja'
        ];

        foreach ($judul as $j) {
            $slug = Str::slug($j);
            $slugOri = $slug;
            $count = 1;
            while (Post::where('slug',$slug)->exists()){
                $slug = $slugOri."-".$count;
                $count++; 
            }


            Post::create([
                'title'=> $j,
                'description'=>'Deskripsi untuk'.$j,
                'slug'=>$slug,
                'content'=>'content untuk'.$j,
                'status'=>'publish',
                'user_id'=>'1'
            ]);
        }
    }
}
