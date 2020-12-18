<?php

namespace App\Http\Controllers;

use App\Question;

class QuestionController extends Controller
{
    public function fetchQuestions($courseId, $pageId = null)
    {
        if ($pageId) {

            $questions = Question::where('course_id', $courseId)
                ->whereRaw('id NOT IN (SELECT question_id FROM question_page WHERE page_id =' . $pageId)
                ->get();
        } else {
            $questions = Question::where('course_id', $courseId)->get();
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rows>';

        foreach ($questions as $question) {

            $type = $pageId ? Day::getDescription($question->type) : $question->type;

            $xml .= "<row id = '" . $question->id . "'>";
            if ($pageId)
                $xml .= "<cell></cell>";
            $xml .= "<cell>" . $question->id . "</cell>";
            $xml .= "<cell><![CDATA[" . $question->title . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $question->text . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $type . "]]></cell>";
            $xml .= "</row>";
        }

        $xml .= "</rows>";

        return response()->xml($xml);

    }

}
