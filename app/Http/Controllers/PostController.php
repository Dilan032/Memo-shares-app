<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('createPost');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $description = $request->description;

        // Regular expression to find base64 encoded images in the description
        $regex = '/<img.*?src="data:image\/(.*?);base64,(.*?)".*?>/';

        // Find all matches in the description
        preg_match_all($regex, $description, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $imgTag) {
                $imageData = $matches[2][$key]; // Base64 encoded image data
                $extension = $matches[1][$key]; // Image extension (e.g., png, jpg)

                // Decode the base64 image data
                $image = base64_decode($imageData);

                // Create a unique file name
                $imageName = '/upload/' . time() . $key . '.' . $extension;

                // Save the image to the public path
                file_put_contents(public_path() . $imageName, $image);

                // Replace the base64 image src with the new file path
                $newImgTag = '<img src="' . $imageName . '">';

                // Replace the old img tag with the new one in the description
                $description = str_replace($imgTag, $newImgTag, $description);
            }
        }

        // Create the post with the updated description
        Post::create([
            'title' => $request->title,
            'description' => $description
        ]);

        return redirect('/');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $description = $request->description;

        // Regular expression to find base64 encoded images in the description
        $regex = '/<img.*?src="data:image\/(.*?);base64,(.*?)".*?>/';
    
        // Find all matches in the description
        preg_match_all($regex, $description, $matches);
    
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $imgTag) {
                $imageData = $matches[2][$key]; // Base64 encoded image data
                $extension = $matches[1][$key]; // Image extension (e.g., png, jpg)
    
                // Decode the base64 image data
                $image = base64_decode($imageData);
    
                // Create a unique file name
                $imageName = '/upload/' . time() . $key . '.' . $extension;
    
                // Save the image to the public path
                file_put_contents(public_path() . $imageName, $image);
    
                // Replace the base64 image src with the new file path
                $newImgTag = '<img src="' . $imageName . '">';
    
                // Replace the old img tag with the new one in the description
                $description = str_replace($imgTag, $newImgTag, $description);
            }
        }
    
        // Create the post with the updated description
        $post->update([
            'title' => $request->title,
            'description' => $description
        ]);
    
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the post by id
        $post = Post::find($id);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        // Get the description content of the post
        $description = $post->description;

        // Regular expression to match all img tags
        $regex = '/<img.*?src=["\'](.*?)["\'].*?>/i';

        // Find all image src attributes in the description
        preg_match_all($regex, $description, $matches);

        // If images are found, proceed to delete them
        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {
                $relativePath = Str::after($src, '/upload/'); // Assuming images are in 'upload' directory
                $fullPath = public_path('upload/' . $relativePath);

                // Check if the file exists and delete it
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        // Delete the post from the database
        $post->delete();

        return redirect()->back()->with('success', 'Post and associated images deleted successfully');
    }



}
