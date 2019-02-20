<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Post;
use App\Topic;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request, Topic $topic)
    {
        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->posts()->save($post);

        return fractal()
            ->item($post)
            ->parseIncludes(['user'])
            ->transformWith(new PostTransformer)
            ->toArray();
    }

    public function update(UpdatePostRequest $request, Topic $topic, Post $post)
    {
        $this->authorize('update', $post);

        $post->body = $request->get('body', $post->body);
        $post->save();

        return fractal()
            ->item($post)
            ->parseIncludes(['user'])
            ->transformWith(new PostTransformer)
            ->toArray();
    }
}
