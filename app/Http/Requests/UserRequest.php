<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 确定用户是否有权发出此请求。 权限功能true 通过
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' .$this->route('user')->id,
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:png,jpg,gif,jpeg|dimensions:min_width=208,min_height=208',
        ];
    }
}
