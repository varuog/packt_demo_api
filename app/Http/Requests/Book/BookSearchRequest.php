<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'filter.title' => 'sometimes|string',
            'filter.language.*' => 'sometimes|string',
            'filter.category.*' => 'sometimes|string',
            'filter.concept.*' => 'sometimes|string',
            'filter.product_type.*' => 'sometimes|string',
            'filter.publish_year.*' => 'sometimes|number',
            'filter.release_year.*' => 'sometimes|number',
            'filter.title' => 'sometimes|string',
            'sort.popular' => 'sometimes|string|in:DESC,ASC',
            'sort.publish_year' => 'sometimes|string|in:DESC,ASC',
            'perPage' => 'sometimes|number',
            'page' => 'sometimes|number',
        ];
    }
}
