<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Project_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    //===========================================================================================================================
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:projects,name|regex:/^[\p{L}\s]+$/u|min:2|max:50',
            'description' => 'required|string|min:20',
            'users' => 'required|array',
            'users.*.id' => 'required|exists:users,id',
            'users.*.role' => 'required|string|in:manager,tester,developer',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'name' => 'اسم المشروع',
            'description' => 'وصف المشروع',
            'users' => 'المستخدمين',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => ' :attribute مطلوب',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'name.min' => 'الحد الأدنى لطول :attribute على الأقل هو 2 حرف',
            'max' => 'الحد الأقصى لطول  :attribute هو 50 حرف',
            'description.string' => 'يجب أن يكون :attribute عبارة عن سلسة نصية',
            'description.min' => 'الحد الأدنى لطول :attribute على الأقل هو 20 محرف',
            'array' => 'يجب أن يكون :attribute عبارة عن مصفوفة',
            'exists' => 'يجب أن يكون :attribute موحودين ضمن جدول المستخدمين',
            'users.string' => 'يجب أن يكون دور :attribute عبارة عن سلسلة نصية',
            'in' => 'يجب أن يكون دور :attribute إحدى الأدوار التالية : manager أو tester أو developer ',
        ];
    }
}
