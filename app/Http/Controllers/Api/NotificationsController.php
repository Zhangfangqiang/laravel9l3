<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
    /**
     * 通知列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();

        return NotificationResource::collection($notifications);
    }

    /**
     * 通知状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        return response()->json([
            'unread_count' => $request->user()->notification_count,
        ]);
    }

    /**
     * 通知已读的方法
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $request->user()->markAsRead();

        return response(null, 204);
    }
}
