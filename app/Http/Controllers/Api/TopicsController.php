<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Queries\TopicQuery;
use App\Http\Resources\TopicResource;
use App\Http\Requests\Api\TopicRequest;

class TopicsController extends Controller
{

    public function index(Request $request, TopicQuery $query)
    {
        $topics = $query->paginate();

        return TopicResource::collection($topics);
    }

    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    public function show($topicId, TopicQuery $query)
    {
        $topic = $query->findOrFail($topicId);
        return new TopicResource($topic);
    }

    /**
     * 创建文章
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    /**
     * 修改文章
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }

    /**
     * 删除话题的方法
     * @param Topic $topic
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return response(null, 204);
    }


}
