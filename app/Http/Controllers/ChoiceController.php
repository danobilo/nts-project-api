<?php

namespace App\Http\Controllers;

use App\Choice;

class ChoiceController extends Controller
{
    public function fetchChoices($questionId)
    {
        $choices = Choice::where('question_id', $questionId)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rows>';

        foreach ($choices as $choice) {

            $xml .= "<row id = '" . $choice->id . "'>";
            $xml .= "<cell></cell>";
            $xml .= "<cell><![CDATA[" . $choice->text . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $choice->response . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $choice->score . "]]></cell>";
            $xml .= "<cell><![CDATA[" . $choice->jumpto . "]]></cell>";
            $xml .= "</row>";
        }

        $xml .= "</rows>";

        return response()->xml($xml);
    }
}
