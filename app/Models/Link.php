<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    use HasFactory;


    public    $cache_key                = 'larabbs_links';      #缓存key

    protected $cache_expire_in_seconds  = 1440 * 60;            #过期时间

    protected $fillable                 = ['title', 'link'];    #可以编辑的字段

    /**
     * 取出所有link 数据并缓存
     * @return mixed
     */
    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function(){
            return $this->all();
        });
    }
}
