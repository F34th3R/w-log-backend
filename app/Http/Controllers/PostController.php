<?php

namespace App\Http\Controllers;

use App\Helpers\GeneratorHelper;
use App\Helpers\HeaderHelper;
use App\Helpers\ImagePostStoreHelper;
use App\Image;
use App\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::where('is_published', 1)->with(['image' => function($query) {
            $query->select('id','path');
        }])->with(['user' => function($query) {
            $query->select('id','code', 'name');
        }])->orderBy('id', 'DESC')->get();
        return response()->json([
            'data' => $post,
        ], 200, HeaderHelper::$copyrigth);
    }
    public function indexByUser()
    {
        $user = Auth::user()->posts()->with(['image' => function($query) {
            $query->select('id','path');
        }])->orderBy('id', 'DESC')->get();
        return response()->json([
            'data' => $user,
        ], 200, HeaderHelper::$copyrigth);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $code = GeneratorHelper::code('POSTS', 6);
        $storeImage = ImagePostStoreHelper::store($request, $code);
        $image = Image::create([
            'path' => $storeImage['path'],
            'tag' => 'post_header'
        ]);
        try
        {
            Post::create([
                'title' => $request->input('title'),
                'code' => $code,
                'user_id' => Auth::id(),
                'image_id' => $image->id,
                'content' => $request->input('content'),
                'is_published' => $request->input('is_published')
            ]);
        } catch (Exception $e) {
            ImagePostStoreHelper::delete($image->id, $code);
            return response()->json([
                'response' => false,
            ], 400, HeaderHelper::$copyrigth);
        }
        return response()->json([
            'response' => true,
        ], 201, HeaderHelper::$copyrigth);
    }

    /**
     * Display the specified resource.
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        if (Post::where('code', $code)->exists()) {
            $post = Post::where(['code' => $code, 'is_published' => 1])->with(['image' => function($query) {
                $query->select('id','path');
            }])->with(['user' => function($query) {
                $query->select('id','code', 'name');
            }])->get();
            return response()->json([
                'data' => $post,
            ], 200, HeaderHelper::$copyrigth);
        }
        return response()->json([
            'data' => false,
        ], 200, HeaderHelper::$copyrigth);
    }
    public function showByUser($code)
    {
        return response()->json([
            'data' => Post::where('code', $code)->exists() ? Post::where(['code' => $code, 'user_id' => Auth::id()])->get() : null,
        ], 200, HeaderHelper::$copyrigth);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
            'is_published' => 'required'
        ]);

        try
        {
            if ($request->input('image')) {
                $image = Image::where('id', $post->image_id)->select('id', 'path')->get();
                ImagePostStoreHelper::update($request, $image[0]->path);
            }
            $post->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'is_published' => $request->input('is_published')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
            ], 400, HeaderHelper::$copyrigth);
        }
        return response()->json([
            'response' => true,
        ], 201, HeaderHelper::$copyrigth);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try
        {
            ImagePostStoreHelper::delete($post->image_id, $post->code);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
            ], 400, HeaderHelper::$copyrigth);
        }
        return response()->json([
            'response' => true,
        ], 201, HeaderHelper::$copyrigth);
    }
}
