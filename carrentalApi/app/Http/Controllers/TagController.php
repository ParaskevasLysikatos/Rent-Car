<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TagController extends Controller
{
    public function search(Request $data)
    {
        $mystring = $data['search'];
        $types = $data['type'];

        // dd($data);

        $tags = Tag::where('title', 'like', "%$mystring%");
        if ($types) {
            $tags = $tags->whereHas('tag_links', function($q) use ($types) {
                $q->whereIn('tag_link_type', explode(',', $types));
            });
        }

        $tags = $tags->take(Cookie::get('pages') ?? '5')->get();
        return response()->json($tags);
    }

    public function preview_api(Request $request)
    {
    $tag = Tag::query()->orderBy('created_at', 'desc');

    return new TagCollection($tag->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));

    }

}
