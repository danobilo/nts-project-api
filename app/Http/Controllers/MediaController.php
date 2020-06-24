<?php

namespace App\Http\Controllers;

use App\Media;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MediaController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $type)
    {
        $this->validate($request, [
            // nullable == optional
            // apache max upload 2mb
            'file' => 'nullable|max:1999'
        ]);

        $project = Project::find($id);
        $project_name = Project::generateProjectId($project->id).'_'.strtolower(str_replace(' ','_',$project->title));



        // Handle File Upload
        if ($request->hasFile('file')) {
            // Get filename with extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('file')->getClientOriginalExtension();
            //Filesize
            $filesize = $request->file('file')->getSize();
            //Filename to store
//            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file('file')->store($project_name.'/'.$type, 'media');
        }

        $user_id = 1;//Auth::id();

        $sort_id = DB::table('media')
            ->select(DB::raw('IF((MAX(sort_id)>0),MAX(sort_id)+1,1) as sort_id'))
            ->where('parent_id', 0)
            ->value('sort_id');

        // create Post
        $media = new Media;
        $media->file_name = $filenameWithExt;
        $media->size = $filesize;
        $media->type = $type;
        $media->extension = $extension;
        $media->sort_id = $sort_id;
        $media->path = $path;
        $media->user_id = $user_id;

        $media->save();

        $message = 'Your file has been added successfully';

        $response = Response::json([
            'state' => true,
            'name' => str_replace("'", "\\'", $filename),
            'extra' => [
                'info' => 'just a way to send some extra data',
                'param' => 'some value here'
            ],
        ]);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
