<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class FormationRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'duree' => 'required|max:255',
            'description' => 'required|max:900',
           
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'error'=>true,
            'message'=>'Erreur de validation',
            'errorsList'=>$validator->errors()
    
        ]));
    }
    public function messages(){
        return[
            'nom.required'=>'un nom doit être fourni',
            'nom.max'=>'Le nom ne doit pas dépassé 255 caractères',
            'duree.required'=>'La durée doit être fourni',
            'duree.max'=>'La durée ne doit pas dépassé 255 caractères',
            'description.required'=>'La description doit être fourni',
            'description.max'=>'La description ne doit pas dépassé 900 caractères',
        ];
    }
}
