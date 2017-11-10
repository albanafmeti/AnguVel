<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Post;
use App\Slim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = new Post();

        if ($request->category_id) {
            $category = Category::find($request->category_id);
            $query = $category->posts();
        }

        if ($request->q) {
            $query = $query->search($request->q);
        }

        $perPage = $request->perPage ?: 6;

        return PostResource::collection($query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->toArray()));
    }

    public function get(Post $post)
    {
        return new PostResource($post);
    }

    public function latest(Request $request, $limit = 4)
    {
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            $q = $category->posts();
        } else {
            $q = Post::orderBy('created_at', 'desc');
        }

        if ($request->featured) {
            $q->where('featured', (string)$request->featured);
        }

        $posts = $q->take($limit)->get();

        return PostResource::collection($posts);
    }

    public function comments(Post $post)
    {
        return CommentResource::collection($post->comments);
    }

    public function addComment(Request $request, Post $post)
    {
        $this->validate($request, [
            'userName' => 'required|min:3',
            'userEmail' => 'required|email|nullable',
            'userComment' => 'required|max:2000|min:10',
        ], [
            'userName.required' => 'Emri eshte i detyrueshem.',
            'userName.min' => 'Emri duhet te kete te pakten 3 karaktere.',
            'userEmail.required' => 'Ju lutem vendosni nje email. Nuk do te publikohet.',
            'userEmail.email' => 'Ju lutem vendosni nje email te sakte.',
            'userComment.required' => 'Komenti eshte fushe e detyrueshme.',
            'userComment.max' => 'Komenti nuk duhet te kete me shume se 2000 karaktere.',
            'userComment.min' => 'Komenti nuk duhet te kete me pak se 10 karaktere.'
        ]);

        $created = $post->comments()->create([
            'content' => $request->userComment,
            'author' => $request->userName,
            'email' => $request->userEmail
        ]);

        if ($created) {
            return response()->json([
                "success" => true,
                "message" => "Comment added successfully."
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Comment addition failed."
        ]);
    }

    public function alternatives(Post $post)
    {
        $previous = Post::where('id', '<', $post->id)->orderBy('id', 'desc')->first();
        $next = Post::where('id', '>', $post->id)->orderBy('id', 'asc')->first();
        return response()->json([
            "previous" => ($previous) ? new PostResource($previous) : null,
            "next" => ($next) ? new PostResource($next) : null,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:15',
            'slug' => 'required|alpha_dash|unique:posts',
            'small_content' => 'required|min:50',
            'content' => 'required',
            'image' => 'required|image',
            'author' => 'required',
            'categories' => 'required'
        ]);

        $request->slug = str_slug($request->slug);
        $request->categories = json_decode($request->categories);

        $post = Post::create([
            'slug' => $request->slug,
            'title' => $request->title,
            'small_content' => $request->small_content,
            'content' => $request->input('content'),
            'author' => $request->author,
            'featured' => $request->featured ? '1' : '0',
            'enabled' => $request->enabled ? '1' : '0',
        ]);

        $post->categories()->attach($request->categories);

        if ($post) {

            if ($request->croppedImage) {

                $object = json_decode($request->croppedImage);

                $filename = uniqid() . "_" . $object->output->name;
                $filepath = "assets/images/posts/" . $filename;

                $data = explode(",", $object->output->image);

                $done = Image::make(base64_decode($data[1]))->save(public_path($filepath));

                if ($done) {
                    $post->image = "posts/$filename";
                    $post->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Post has been created successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Post creation failed.'
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:15',
            'slug' => 'required|alpha_dash|unique:posts,slug,' . $post->id . ',id',
            'small_content' => 'required|min:50',
            'content' => 'required',
            'image' => 'nullable|image',
            'author' => 'required',
            'categories' => 'required'
        ]);

        $request->slug = str_slug($request->slug);
        $request->categories = json_decode($request->categories);

        $saved = $post->update([
            'slug' => $request->slug,
            'title' => $request->title,
            'small_content' => $request->small_content,
            'content' => $request->input('content'),
            'author' => $request->author,
            'featured' => $request->featured ? '1' : '0',
            'enabled' => $request->enabled ? '1' : '0',
        ]);

        if ($saved) {

            $post->categories()->detach();
            $post->categories()->attach($request->categories);

            if ($request->croppedImage) {

                $object = json_decode($request->croppedImage);

                $filename = uniqid() . "_" . $object->output->name;
                $filepath = "assets/images/posts/" . $filename;

                $data = explode(",", $object->output->image);

                $done = Image::make(base64_decode($data[1]))->save(public_path($filepath));

                // Delete the old image
                if ($post->image) {
                    File::delete(public_path('assets/images/' . ltrim($post->image, '/')));
                }

                if ($done) {
                    $post->image = "posts/$filename";
                    $post->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Post has been saved successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Post update failed.'
        ]);
    }


    public function delete(Post $post)
    {
        $deleted = $post->delete();
        if ($deleted) {

            // Delete the image
            if ($post->image) {
                File::delete(public_path('assets/images/' . ltrim($post->image, '/')));
            }
            return response()->json([
                'success' => true,
                'message' => 'Post has been deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Post deletion failed.'
        ]);
    }
}
