<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Http\Resources\ChapterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'parent' => 'required'
        ]);

        $parent_id = $request->get('parent');
        $user_id = 1; //Auth::id();

        if ($request->get('document')) {
            $document_id = $request->get('document');
        } else {
            $document_id = Chapter::where('id', $parent_id)->first()->document_id;
        }

        $sort_id = DB::table('chapters')
            ->select(DB::raw('IF((MAX(sort_id)>0),MAX(sort_id)+1,1) as sort_id'))
            ->whereRaw('document_id = ' . $document_id . ' AND parent_id =  ' . $parent_id)
            ->value('sort_id');


        $chapter = new Chapter(array(
            'title' => $request->get('title'),
            'document_id' => $document_id,
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'sort_id' => $sort_id
        ));

        if ($chapter->save()) {
            $response = Response::json([
                'success' => true,
                'message' => 'The chapter has been created succesfully',
                'id' => $chapter->id,
            ]);
        } else {

            $response = Response::json([
                'message' => 'An error occurred while saving',
                'success' => false
            ]);
        }


        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chapter = collect(new ChapterResource(Chapter::find($id)));

        $chapter->toArray();

        return response()->xml($chapter, $status = 200, [], $xmlRoot = 'data');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->title) {

            $response = Response::json([
                'error' => [
                    'message' => 'Please enter all required fields'
                ]
            ], 422);
            return $response;
        }

        $chapter = Chapter::find($id);
        $chapter->title = $request->title;
        $chapter->save();

        $response = Response::json([
            'success' => true,
            'message' => 'The chapter has been updated.',
            'data' => $chapter,
        ]);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            $response = Response::json([
                'error' => [
                    'message' => 'The chapter cannot be found.'
                ]
            ], 404);

            return $response;
        }

        $response = Response::json([
            'success' => true,
            'message' => 'The chapter has been deleted.'

        ]);

        return $response;
    }

    public function showContent($id)
    {
        $chapter = Chapter::find($id);

        $response = Response::json([
            'success' => true,
            'content' => $chapter->content
        ]);

        return $response;

    }

    public function updateContent(Request $request, $id)
    {

        $chapter = Chapter::find($id);

        $chapter->content = $request->notes;

        if ($chapter->save()) {

            $message = 'The chapter has been updated';

            $response = Response::json([
                'message' => $message,
                'chapter' => $chapter->id,
                'success' => true
            ]);
        } else {
            $message = 'An error occurred while saving';

            $response = Response::json([
                'message' => $message,
                'success' => false
            ]);
        }


        return $response;
    }
}
