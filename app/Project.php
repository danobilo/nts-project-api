<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];

    public function types()
    {
        return $this->belongsToMany(Type::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public static function generateProjectId($itemId)
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
