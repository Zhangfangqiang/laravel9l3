<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Resources\LinkResource;

class LinksController extends Controller
{
    /**
     * 资源推荐列表
     * @param Link $link
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection]
     */
    public function index(Link $link)
    {
        $links = $link->getAllCached();

        LinkResource::wrap('data');
        return LinkResource::collection($links);
    }
}
