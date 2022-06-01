<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\QueryBuilder;

class Topic extends Model
{
    use HasFactory;

    /**
     * 可编辑的参数
     * @var string[]
     */
    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'
    ];
    /**
     * 反向一对一
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 反向一对一
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 根据参数切换两种排序方式
     * @param $query
     * @param $order
     */
    public function scopeWithOrder($query, $order)
    {
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
    }

    /**
     * 按照更新时间排序
     * @param $query
     * @return mixed
     */
    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * 按照创建时间排序
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 有好的链接展示
     * @param array $params
     * @return string
     */
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    /**
     * 一对多回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 更新评论数的方法
     */
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }

    /**
     * 重写路由模型绑定 方法一
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model|object|QueryBuilder|null
    public function resolveRouteBinding($value)
    {
        return QueryBuilder::for(self::class)
            ->allowedIncludes('user', 'category')
            ->where($this->getRouteKeyName(), $value)
            ->first();
    }*/
}
