<?php

namespace App\Console\Commands;

use App\Mail\CheckCronjob;
use App\Models\Video;
use Illuminate\Console\Command;

class VideoUpdateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:UpdateData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set order to fail status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $page = \Cache::get('page_video') ?? 1;
        $video = Video::where('status', '>', 1 )
            ->orderBy('id', 'DESC')
            ->paginate(20, ['*'], 'page', $page);
        \Cache::set('page_video', $page);
        foreach ($video as $item){
            if (!empty($item->video_id)){
                $data_video = \Lib::youtube_data_custome($item->video_id);
                if (!empty($data_video)){
                    $item->update([
                        'title' => $data_video['title'],
                        'image_thumbnail' => $data_video['thumbnails']['medium'],
                        'view_count' => $data_video['count']['viewCount'],
                        'published_at' => $data_video['publishedAt'],
                        'channel_id' => $data_video['channelId'],
                        'updated' => strtotime(now())
                    ]);
                }
                \MyLog::do()->add('System Update Video Infomation', $item->id);
            }
        }
        if (\Cache::get('page_video') < $video->lastPage()){
            $page += 1;
            \Cache::set('page_video', $page);
        }
        if (\Cache::get('page_video') == $video->lastPage()){
            $page = 1;
            \Cache::forget('page_video');
        }
    }
}