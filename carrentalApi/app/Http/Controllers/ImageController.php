<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Image;
use App\ImageLink;
use Illuminate\Http\Request;
use Validator;

class ImageController extends Controller
{
    public function removeImageLink(Request $data) {
        $validator = Validator::make($data->all(), [
            'image_id' => 'required|exists:images,id',
            'image_link_id' => 'required',
            'image_link_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ($validator->errors()->first());
        }

        ImageLink::where(['image_id' => $data['image_id'], 'image_link_id' => $data['image_link_id'], 'image_link_type' => $data['image_link_type']])->delete();
        return response()->json(['message' => 'Successful removal of image']);
    }


    // the system does not deletes, only unlinks from db, becomes orphan image
    public function uploadImage(Request $data)
    {
        $path = 'images/';
        $file = $data->file('file');
        $stored_path=$file->store('public/'.$path);
        // $data->file('image')->storeAs('public/images', $fileName);

        $image            = new Image();
        //$image->path      = $path.$file->getClientOriginalName();
        $image->path = str_replace("public/", "", $stored_path);
        $image->mime_type = $file->getMimeType();
        $image->save();

        return new ImageResource($image);
    }

    public function delete(Request $request)
    {

    }


}