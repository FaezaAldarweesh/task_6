<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Project_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $project_id = $this->route('project');
        
        return [
            'name' => ['nullable','regex:/^[\p{L}\s]+$/u','min:2','max:50',Rule::unique('projects', 'name')->ignore($project_id)], 
            'description' => 'nullable|string|min:20',
            'users' => 'nullable|array',
            'users.*.id' => 'nullable|exists:users,id',
            'users.*.role' => 'nullable|string|in:manager,tester,developer',
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
            'name' => 'اسم المستخدم',
            'description' => 'وصف المشروع',
            'users' => 'المستخدمين',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
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