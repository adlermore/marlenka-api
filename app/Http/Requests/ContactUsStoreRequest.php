<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class ContactUsStoreRequest extends FailedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'phone_number' => 'required|string|max:150',
            'whatsapp' => 'required|string|max:150',
            'email' => 'required|string|email|max:150',
            'facebook_link' => 'required|string|max:150',
            'instagram_link' => 'required|string|max:150',
            'gmail_link' => 'required|string|max:150',
            'linkedin_link' => 'required|string|max:150',
            'twitter_link' => 'required|string|max:150',
            'youtube_link' => 'required|string|max:150',
        ];


        return $rule;

    }

/*    public function messages()
    {
        return [
            'id.exists' => __('variable.not_found_error'),
            'name.required' => __('variable.this_field_is_required'),
            'name.string' => __('variable.this_field_is_required_and_has_been_text'),
            'name.max' => __('variable.max_150_error'),
            'email.required' => __('variable.this_field_is_required'),
            'email.email' => __('variable.wrong_email'),
            'email.unique' => __('variable.already_exist_error'),
            'email.max' => __('variable.max_150_error'),
            'password.required' => __('variable.this_field_is_required'),
            'password.confirmed' => __('variable.wrong_passwords'),
            'password.min' => __('variable.min_password_error'),
            'role_id.*.exists' => __('variable.not_found_error'),
            'permission_id.*.exists' => __('variable.not_found_error'),
            'organization_id.required' => __('variable.this_field_is_required'),
        ];
    }*/
}
