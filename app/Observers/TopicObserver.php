<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
use App\Handlers\SlugTranslateHandler;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->body    = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (!$topic->slug) {
            #队列
            dispatch(new TranslateSlug($topic));
        }
    }


    public function saved(Topic $topic)
    {
        if (!$topic->slug) {
            #队列
            dispatch(new TranslateSlug($topic));
        }
    }

    /**
     * 删除文章的时候删除连带的评论
     * @param Topic $topic
     */
    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }



}

