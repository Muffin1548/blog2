<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    private $canPost;

    public function authorize(): bool
    {
        $this->canPost = $this->user()->canPost();
        if ($this->canPost){
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            'title' => 'required|unique:posts|max:255',
            'title' => array('Regex:/^[A-Za-z0-9 ]+$/'),
            'body' => 'required',
        ];
    }
}
