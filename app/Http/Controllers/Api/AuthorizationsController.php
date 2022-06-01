<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\AuthorizationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthorizationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('update', 'destroy');
    }

    /**
     * 第三方登录
     * @param $type
     * @param SocialAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::create($type);        #专门用于授权登录的包

        try {
            if ($code = $request->code) {
                $oauthUser = $driver->userFromCode($code);
            } else {
                // 微信需要增加 openid
                if ($type == 'wechat') {
                    $driver->withOpenid($request->openid);
                }

                $oauthUser = $driver->userFromToken($request->access_token);
            }
        } catch (\Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        if (!$oauthUser->getId()) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'wechat':
                $unionid = $oauthUser->getRaw()['unionid'] ?? null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 没有用户，默认创建一个用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;
        }

        return $this->respondWithToken($user->createToken('api')->plainTextToken);
    }

    /**
     * 登录
     */
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $where[] = ['email', '=', $username];
        } else {
            $where[] = ['phone', '=', $username];
        }

        $user = User::where($where)->first();
        if (!$user ||  !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(403, trans('auth.failed'), 1003);
        }


        return $this->respondWithToken($user->createToken('api')->plainTextToken);
    }

    /**
     * 更新token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->respondWithToken($request->user()->createToken('api')->plainTextToken);
    }

    /**
     * 删除token
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response(null, 204);
    }

    /**
     * 返回token
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Carbon::tomorrow()->format('Y-m-d H:i:s')
        ]);
    }
}
