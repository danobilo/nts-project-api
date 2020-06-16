<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //transforms the resource into an array made up of the attributes to be converted to JSON
        return [
            'id' => $this->id,
            'project_id' => $this->generateProjectId($this->id),
            'title' => $this->title,
            'goal' => $this->goal,
            'input' => $this->input,
            'output' => $this->output,
//            'created_by' => $this->creator->name,
            'is_published' => $this->is_published
        ];
    }

    public function generateProjectId($itemId)
    {
        if (strlen($itemId) == 1) {
            $projectId = "P00000" . $itemId . "";
        } else if (strlen($itemId) == 2) {
            $projectId = "P0000" . $itemId . "";
        } else if (strlen($itemId) == 3) {
            $projectId = "P000" . $itemId . "";
        } else if (strlen($itemId) == 4) {
            $projectId = "P00" . $itemId . "";
        } else if (strlen($itemId) == 5) {
            $projectId = "P0" . $itemId . "";
        } else {
            $projectId = $itemId;
        }

        return $projectId;
    }

}
