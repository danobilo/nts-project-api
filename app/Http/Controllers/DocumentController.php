<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use stdClass;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pId)
    {
        $documents = Document::whereHas('projects', function ($query) use ($pId) {
            $query->where("document_project.project_id", "=", $pId);
        })->get();

        $chapters = Chapter::all();

        $objects = array();
        $roots = array();
        foreach ($chapters as $chapter) {
            if (!isset($objects[$chapter->id])) {
                $objects[$chapter->id] = new stdClass;
                $objects[$chapter->id]->children = array();
            }

            $obj = $objects[$chapter->id];
            $obj->title = $chapter->title;
            $obj->id = $chapter->id;
            $obj->sort = $chapter->sort_id;
            $obj->document_id = $chapter->document_id;
            $obj->author = $chapter->user_id;
            $obj->created_at = $chapter->created_at;

            if ($chapter->parent_id == 0) {
                $roots[$chapter->document_id][] = $obj;
            } else {
                if (!isset($objects[$chapter->parent_id])) {
                    $objects[$chapter->parent_id] = new stdClass;
                    $objects[$chapter->parent_id]->children = array();
                }

                $objects[$chapter->parent_id]->children[$chapter->id] = $obj;
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rows>';

        foreach ($documents as $document) {
            $xml .= '<row id="doc_' . $document->id . '">';
            $xml .= '<cell>' . $document->id . '</cell>';
            $xml .= "<cell image=\"folder.gif\"><![CDATA[" . $document->title . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $document->user_id . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $document->created_at . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $document->is_published . "]]></cell>";

            if (isset($roots[$document->id])) {
                foreach ($roots[$document->id] as $key => $obj) {
                    $xml .= $this->printElementsTreeGridXML($obj, '', true);
                }
            }

            $xml .= '</row>';
        }
        $xml .= '</rows>';

        return response()->xml($xml);
    }

    function printElementsTreeGridXML(stdClass $obj, $chapter, $isRoot = false)
    {

        if ($isRoot) {
            $chapter = $obj->sort;
        } else {
            $chapter .= '.' . $obj->sort;
        }

        $xml = '<row id="' . $obj->id . '">';
        $xml .= '<cell>' . $chapter . '</cell>';
        if (count($obj->children) == 0) {
            $xml .= "<cell><![CDATA[" . $obj->title . "]]></cell>";
        } else {
            $xml .= "<cell><![CDATA[" . $obj->title . "]]></cell>";
        }
        $xml .= "<cell><![CDATA[" . $obj->author . "]]></cell>";
        $xml .= "<cell><![CDATA[" . $obj->created_at . "]]></cell>";

        foreach ($obj->children as $child) {
            $xml .= $this->printElementsTreeGridXML($child, $chapter);
        }
        $xml .= '</row>';

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
            'title' => 'required',
            'id' => 'required'
        ]);

        $user_id = 1;//Auth::id();

        $document = new Document(array(
            'title' => $request->get('title'),
            'category' => $request->get('category'),
            'user_id' => $user_id
        ));

        if ($document->save()) {
            $document->projects()->attach($request->get('id'));

            $response = Response::json([
                'success' => true,
                'message' => 'Document created succesfully',
                'id' => 'doc_' . $document->id
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
    public function destroy(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            $response = Response::json([
                'error' => [
                    'message' => 'The document cannot be found.'
                ]
            ], 404);

            return $response;
        }

        $document->projects()->detach($request->get('pId'));

        $response = Response::json([
            'success' => true,
            'message' => 'The document has been deleted.'

        ]);

        return $response;
    }
}
