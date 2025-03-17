<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|after:start_time|date_format:H:i',
            'start_rest' => 'date_format:H:i|after:start_time|before:end_time',
            'end_rest' => 'date_format:H:i|after:start_rest|before:end_time',
            'note' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'start_time.required' => '出勤時間を入力してください',
            'end_time.required' => '退勤時間を入力してください',
            'end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'start_rest.after' => '休憩時間が勤務時間外です',
            'end_rest.after' => '休憩終了時間は休憩開始時間よりも後の時間を入力してください',
            'end_rest.before' => '休憩時間が勤務時間外です',
            'note.required' => '備考を入力してください',
        ];
    }
}
