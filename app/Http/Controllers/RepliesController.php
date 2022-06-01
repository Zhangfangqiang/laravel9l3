<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 创建回复api
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReplyRequest $request, Reply $reply)
    {
        $reply->content  = $request->content;
        $reply->user_id  = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

        return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
    }

    /**
     * 修改回复页面
     * @param Reply $reply
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function edit(Reply $reply)
	{
        $this->authorize('update', $reply);
		return view('replies.create_and_edit', compact('reply'));
	}

    /**
     * 修改回复api
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function update(ReplyRequest $request, Reply $reply)
	{
		$this->authorize('update', $reply);
		$reply->update($request->all());

		return redirect()->route('replies.show', $reply->id)->with('message', '评论修改成功');
	}

    /**
     * 删除回复api
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
	}
}
