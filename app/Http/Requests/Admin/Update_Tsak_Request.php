<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Tsak_Request extends FormRequest
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
        $task_id = $this->route('task');
        return [
            'title' => ['nullable' , 'regex:/^[\p{L}\s]+$/u' , 'min:2', Rule::unique('tasks', 'title')->ignore($task_id)],
            'description' => 'nullable|string|min:10|max:150',
            'status' => 'nullable|string|in:New,In progress,Done',
            'priority' => 'nullable|string|in:High,Medium,Low',
            'due_date' => 'nullable|date|after_or_equal:today',
            'project_id' => 'nullable|integer|exists:projects,id',
            'notes' => 'nullable|string|min:10|max:150',
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
            'title' => 'عنوان المهمة',
            'description' => 'وصف المهمة',
            'status' => 'حالة المهمة',
            'priority' => 'درجة أهمية المهمة',
            'due_date' => 'تاريخ التسليم',
            'project_id' => 'اسم الموظف',
            'notes' => 'ملاحطات التاسك',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'string' => 'يحب أن يكون الحقل :attribute يحوي محارف',
            'max' => 'الحد الأقصى لطول  :attribute هو 150 حرف',
            'status.in' => 'يجب أن يكون دور :attribute إحدى الأدوار التالية : New أو In progress أو Done ',
            'priority.in' => 'يجب أن تكون قيمة الحقل إحدى القيم التالية : High,Medium,Low',
            'date' => 'يجب أن يكون الحقل :attribute تاريخاً',
            'after_or_equal' => 'يجب أن بكون :attribute بتاريخ اليوم و ما بعد',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'exists' => 'يجب أن يكون :attribute موجودا ضمن جدول الموظفين',
            'description.min' => 'الحد الأدنى لطول :attribute على الأقل هو 10 حرف',
            'title.min' => 'الحد الأدنى لطول :attribute على الأقل هو 2 حرف',
            'notes.min' => 'الحد الأدنى لطول :attribute على الأقل هو 10 حرف',
        ];
    }
}
