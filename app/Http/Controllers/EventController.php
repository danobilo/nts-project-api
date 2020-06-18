<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Document;
use App\Event;
use App\Http\Resources\EventResource;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pId, $type, $id)
    {
        $colId = 'document_id';

        if ($type == 'chapter') {
            $colId = 'chapter_id';
        }

//        $events = Event::with(['creator', 'users'])->where([['project_id', '=', $projectId], [$tableId, '=', $queryId], ['parent_id', '=', 0]])->get();

        $events = Event::where([['project_id', '=', $pId], ["{$colId}", '=', $id], ['parent_id', '=', 0]])->get();



        $xml = self::get_events_xml($events);

        return response()->xml($xml);
    }

    public static function get_events_xml(&$events)
    {

        $xml = '<rows>';
        foreach ($events as $event) {

//            $assigned = array();
//            foreach ($event->users as $user) {
//                $assigned[] = $user->name;
//            }

            $xml .= "<row id='{$event->id}'>";
            $xml .= "<cell>" . $event->id . "</cell>";
            $xml .= "<cell><![CDATA[" . $event->details . "]]></cell>";
//            $xml .= "<cell><![CDATA[" . $event->creator->name . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $event->user_id . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $event->user_id . "]]></cell>";
//            $xml .= "<cell><![CDATA[" . implode(", ", $assigned) . "]]></cell>";
            $xml .= "<cell>" . (string)$event->start_date . "</cell>";
            $xml .= "<cell>" . (string)$event->end_date . "</cell>";
            $xml .= "<cell>" . $event->is_visible . "</cell>";
            $xml .= "<cell>" . $event->status . "</cell>";
            $xml .= "</row>";
        }
        $xml .= '</rows>';

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
            'project_id' => 'required'
        ]);

        if ($request->chapter_id) {
            $chapter_id = $request->chapter_id;
            $chapter = Chapter::where('id', $request->chapter_id)->first();
            $document_id = $chapter->document_id;
            $doc_name = $chapter->title;
        }

        if ($request->document_id) {
            $doc_name = Document::where('id', $request->document_id)->first()->title;
            $document_id = $request->document_id;
            $chapter_id = 0;

        }

        $project_name = Project::where('id', $request->project_id)->first()->title;
        $details = Project::generateProjectId($request->project_id) . ' | ' . $project_name . ' | ' . $doc_name;

        $user_id = 1; //Auth::id();

        $event = new Event(array(
            'title' => $details,
            'details' => $details,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
            'project_id' => $request->project_id,
            'document_id' => $document_id,
            'chapter_id' => $chapter_id,
            'user_id' => $user_id
        ));
        $event->save();
//        $event->users()->attach($user_id);

        $response = Response::json([
            'success' => true,
            'message' => 'The event has been created succesfully',
            'id' => $event->id
//            'data' => new EventResource($event),
        ]);

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
        $event = Event::find($id);

        if (!$event) {

            $response = Response::json([
                'error' => [
                    'message' => 'The event cannot be found.'
                ]
            ], 404);

            return $response;
        }

        Event::destroy($id);

        $response = Response::json([
            'success' => true,
            'message' => 'The event has been deleted.'
        ]);

        return $response;
    }
}
