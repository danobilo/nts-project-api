<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ['id'];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
