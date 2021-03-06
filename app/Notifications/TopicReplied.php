<?php

namespace App\Notifications;

use JPush\PushPayload;                              #极光推送包
use App\Notifications\Channels\JPushChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicReplied extends Notification  implements ShouldQueue  #implements ShouldQueue 代表使用了队列
{
    use Queueable;

    public $reply;

    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }


    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database','mail',JPushChannel::class];
    }

    /**
     * 数据库通知
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);

        // 存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }

    /**
     * 邮件通知
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
            ->line('你的话题有新回复！')
            ->action('查看回复', $url);
    }

    /**
     * 一个回调方法
     * @param $notifiable
     * @param PushPayload $payload
     * @return PushPayload
     */
    public function toJPush($notifiable, PushPayload $payload): PushPayload
    {
        return $payload
            ->setPlatform('all')
            ->addRegistrationId($notifiable->registration_id)
            ->setNotificationAlert(strip_tags($this->reply->content));
    }
}
