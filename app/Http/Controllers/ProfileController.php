<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\TranslateValidation;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public $minSize = 2;
    public $maxSize = 191;


    public function index()
    {
        return response()->json(auth()->user());
    }


    public function rules()
    {
        return  [
            'person.first_name' => "required|regex:/^[^\$%\^\*£=~@#!]+$/u|min:{$this->minSize}|Max:{$this->maxSize}",
            'person.last_name' => "required|regex:/^[^\$%\^\*£=~@#!]+$/u|min:{$this->minSize}|Max:{$this->maxSize}",
            'emailaddress' => 'email',
            'person.mobilenumber' => 'nullable|numeric',
            'person.faxnumber' => 'nullable|numeric',
            'person.phonenumber' => 'nullable|numeric',            
            'person.date_of_birth' => 'before:today'            
        ];
    }
    public function messages()
    {
        return [
            'required' => "required",
            'min' => "min_error",
            'max' => "max_error",
            'email' => 'email',
            'before' => "before_today_error",
            'regex'=>"invaild_format"
        ];
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Form  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->rules(),$this->messages());
        if ($validator->fails()) {          
            $errorMessageArr =
            TranslateValidation::translateMessage($validator->messages(),$this->messages(),$request->lang,$this->minSize,$this->maxSize);
           return response()->json(["message" => $errorMessageArr],201);
        }

        if (!auth()->user()->person) {
            auth()->user()->person()->create(

                [
                    'initials' => $request->person['initials'],
                    'first_name' => $request->person['first_name'],
                    'insertion' => $request->person['insertion'],
                    'last_name' => $request->person['last_name'],
                    'date_of_birth' => $request->person['date_of_birth'],
                    'phonenumber' => $request->person['phonenumber'],
                    'mobilenumber' => $request->person['mobilenumber'],
                    'faxnumber' => $request->person['faxnumber'],
                    'gender' => $request->person['gender'],
                    'place_of_birth' => $request->person['place_of_birth'],
                    'nationality' => $request->person['nationality']
                ]
            );
        } else {
            auth()->user()->person()->update(
                [
                    'initials' => $request->person['initials'],
                    'first_name' => $request->person['first_name'],
                    'insertion' => $request->person['insertion'],
                    'last_name' => $request->person['last_name'],
                    'date_of_birth' => $request->person['date_of_birth'],
                    'phonenumber' => $request->person['phonenumber'],
                    'mobilenumber' => $request->person['mobilenumber'],
                    'faxnumber' => $request->person['faxnumber'],
                    'gender' => $request->person['gender'],
                    'place_of_birth' => $request->person['place_of_birth'],
                    'nationality' => $request->person['nationality']
                ]
            );
        }


        return response()->json(auth()->user()->person);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $request = Request();
        if ($request->input('restore')) {
            return response()->json($user->restore());
        }

        if ($user->trashed()) {
            return response()->json($user->forceDelete());
        }
        return response()->json($user->delete());
    }
}
