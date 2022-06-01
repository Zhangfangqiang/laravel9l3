<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $notifications = Auth::user()->notifications()->paginate(20);   // 获取登录用户的所有通知
        Auth::user()->markAsRead();                                     //通知数量清零
        return view('notifications.index', compact('notifications'));
    }
}
