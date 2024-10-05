<?php

namespace App\Http\Requests\Backend\Instructors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AddNewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation()
    {
        $this->merge([
            'social_facebook' => $this->addHttpsIfMissing($this->social_facebook),
            'social_twitter' => $this->addHttpsIfMissing($this->social_twitter),
            'social_instagram' => $this->addHttpsIfMissing($this->social_instagram),
            'social_linkedin' => $this->addHttpsIfMissing($this->social_linkedin),
            'social_youtube' => $this->addHttpsIfMissing($this->social_youtube),
        ]);

        // Log the modified data for debugging purposes
        Log::info('Prepared Data: ', $this->all());
    }

    private function addHttpsIfMissing($url)
    {
        // Only add 'https://' if the URL is not empty and does not already have a protocol
        if ($url && !preg_match('/^https?:\/\//', $url)) {
            return 'https://' . $url;
        }
        return $url;
    }

    public function rules(): array
    {
        return [
            'fullName_en' => 'required|max:255',
            'emailAddress' => 'required|unique:instructors,email',
            'contactNumber_en' => 'required|unique:instructors,contact_en',
            'roleId' => 'required|max:3',
            'password' => 'required',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_youtube' => 'nullable|url',
        ];
    }

}
