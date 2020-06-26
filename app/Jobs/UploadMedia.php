<?php

namespace App\Jobs;


use App\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UploadMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.+
     *
     * @return void
     */
    public function handle()
    {
//        $this->request->file('file')->storeAs($this->request->path, $this->request->file_name, 'public');

        $path = $this->media->file('file')->store('projects', 'public');
//        $path = $this->media->tmp_path->store('projects', 'public');

//        Storage::disk('public')->put('projects/1', $this->media->tmp_path);
    }
}
