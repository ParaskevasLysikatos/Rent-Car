<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgramCollection;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ProgramController extends Controller
{
    public function preview_api(Request $request) {
        $programs = Program::query();
        return new ProgramCollection($programs->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $program = Program::find($id);

        return new ProgramResource($program);
    }

    public function search_ajax(Request $data, $lng) {
        $search = $data['search'];

        $programs = Program::where('id', $search)->orWhere('slug', 'like', "%" . $search . "%")
            ->orWhereHas("profiles", function ($q) use ($search, $lng) {
                $q->where("title", "like", "%" . $search . "%");
                $q->where('language_id', $lng);
            })->take(Cookie::get('pages') ?? '5')->get(['id']);
        $programs->each->append('profile_title');

        return response()->json($programs);
    }
}
