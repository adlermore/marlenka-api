<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class HomeSliderStoreRequest extends FailedValidation
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
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:150',
            'product_id' => 'array',
        ];

        if ($this->has("image_path") and $this->hasFile("image_path")) {
            $rule['image_path'] = 'required|mimes:jpeg,png,jpg';
        }
        if ($this->has("small_image_path") and $this->hasFile("small_image_path")) {
            $rule['small_image_path'] = 'required|mimes:jpeg,png,jpg';
        }

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
