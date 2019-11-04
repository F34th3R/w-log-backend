<?php


namespace App\Helpers;


use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImagePostStoreHelper
{
    public static function store(Request $request, string $code)
    {
        $imageName = str_replace('#', '', Auth::user()->code).'/'.str_replace('#', '',$code).'/'.'f34th3r.io_'.str_random(50);

        $exploded = explode(',', $request->input('image'));
        $decode = base64_decode($exploded[1]);
        $extension = str_contains($exploded[0], 'jpeg') ? 'jpg' : 'png';
        $fileName = $imageName.'.'.$extension;
        Storage::disk('post_header')->put($fileName, $decode);

        return [
            'imageName' => $imageName,
            'fileName' => $fileName,
            'path' => '/storage/img-post-header/'.$fileName
        ];
    }

    public static function update(Request $request, string $imagePath)
    {
        $imageName = str_replace('/storage/img-post-header/', '', $imagePath);
        // Delete old image
        Storage::disk('post_header')->delete($imageName);

        // Store the new image with the old name path
        $exploded = explode(',', $request->input('image'));
        $decode = base64_decode($exploded[1]);
        Storage::disk('post_header')->put($imageName, $decode);

        return [
            'path' => '/storage/img-post-header/'.$imageName
        ];
    }

    public static function delete($image_id, $code)
    {
        $folderPath = str_replace('#', '', Auth::user()->code).'/'.str_replace('#', '',$code);
        Storage::disk('post_header')->deleteDirectory($folderPath);
        Image::where('id', $image_id)->delete();
    }
}
