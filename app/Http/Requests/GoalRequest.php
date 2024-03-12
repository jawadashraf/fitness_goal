<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class GoalRequest extends FormRequest
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
        $method = strtolower($this->method());

        $rules = [];
        switch ($method) {
            case 'post':
                $rules = [
                    'title' => 'required',
                    'goal_type_id' => 'required',
                    'unit_type_id' => 'required',
                    'target_value' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ];
                break;
            case 'patch':
                $rules = [
                    'title' => 'required',
                    'goal_type_id' => 'required',
                    'unit_type_id' => 'required',
                    'target_value' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'goal_type_id.required' => __('validation.required', ['attribute' => __('message.goal_type')]),
            'unit_type_id.required' => __('validation.required', ['attribute' => __('message.unit_type')]),
        ];
    }

     /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status' => true,
            'message' => $validator->errors()->first(),
            'all_message' =>  $validator->errors()
        ];

        if ( request()->is('api*')){
           throw new HttpResponseException( response()->json($data,422) );
        }

        if ($this->ajax()) {
            throw new HttpResponseException(response()->json($data,422));
        } else {
            throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
        }
    }
}
