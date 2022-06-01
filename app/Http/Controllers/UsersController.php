<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    /**
     * 构造方法
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);      //排除show
    }

    /**
     * 展示页
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 更新页
     * @param User $user
     * @param UserRequest $request
     * @param ImageUploadHandler $uploader
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, UserRequest $request, ImageUploadHandler $uploader)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    /**
     * 编辑页
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
}
