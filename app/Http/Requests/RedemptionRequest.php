<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class RedemptionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'participation_id' => ['required', 'string'],
            'method'           => ['required', 'string', 'in:store,mail,pickup'],
        ];
    }
}
