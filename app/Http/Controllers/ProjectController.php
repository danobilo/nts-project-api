<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use stdClass;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::where('is_visible', '=', 1)
            ->orderByRaw('parent_id = 0', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $objects = array();
        $roots = array();
        foreach ($projects as $project) {
            if (!isset($objects[$project->id])) {
                $objects[$project->id] = new stdClass;
                $objects[$project->id]->children = array();
            }

            $obj = $objects[$project->id];
            $obj->title = $project->title;
            $obj->id = $project->id;
            $obj->parent_id = $project->parent_id;

            if ($project->parent_id == 0) {
                $roots[] = $obj;
            } else {
                if (!isset($objects[$project->parent_id])) {
                    $objects[$project->parent_id] = new stdClass;
                    $objects[$project->parent_id]->children = array();
                }

                $objects[$project->parent_id]->children[$project->id] = $obj;
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<tree id="0">';

        foreach ($roots as $obj) {
            $xml .= $this->printXML($obj, true);
        }

        $xml .= '</tree>';

        return response()->xml($xml);
    }

    public function printXML(stdClass $obj, $isRoot = false)
    {

        $xml = "<item id='" . $obj->id . "' text='" . $obj->title . "'>";

        foreach ($obj->children as $child) {
            $xml .= $this->printXML($child);
        }

        $xml .= '</item>';

        return $xml;
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
            'title' => 'required'
        ]);

        $user_id = 1;

        $project = new Project(array(
            'title' => $request->get('title'),
            'parent_id' => !($request->get('parent_id')) ? 0 : $request->get('parent_id'),
            'user_id' => $user_id
        ));

        if ($project->save()) {
//            $project->users()->attach($user_id);

            $message = 'The project has been created succesfully';

            $response = Response::json([
                'message' => $message,
                'project' => $project,
                'success' => true
            ], 201);
        } else {
            $message = 'An error occurred while saving';

            $response = Response::json([
                'message' => $message,
                'success' => false
            ], 200);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            $response = Response::json([
                'error' => [
                    'message' => 'The project cannot be found.'
                ]
            ], 404);

            return $response;
        }

        $project->is_visible = 0;
        $project->save();

        $response = Response::json([
            'success' => true,
            'message' => 'The project has been deleted.'
        ], 200);

        return $response;
    }
}
