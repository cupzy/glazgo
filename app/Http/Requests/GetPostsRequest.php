<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetPostsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'format' => Rule::in(['xlsx', 'csv', 'ods']),
            'file' => 'file|mimes:xlsx,csv,ods',
        ];
    }
}
