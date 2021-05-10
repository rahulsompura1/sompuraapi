<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormFields;
use Illuminate\Http\Request;

class FormFieldsController extends Controller
{

    public function create(Request $request) {

        $formField = FormFields::create($request->all());
         return response()->json([
            "message" => "Form field record created",
            "formField" => $formField
        ], 200);
    }

    public function update(Request $request,FormFields $formfield) {
       if ($formfield->update($request->all())) {
            return response()->json(["message" => "Records updated successfully"], 200);
        } else {
            return response()->json(["message" => "Something wrong happen. Please try again"], 504);
        }
    }
    /**
     * Get all the Categories from the database table.
     */

    public function getFormByID(Request $request)
    {
        $userID = $request->get('user_id');
        
        return FormFields::where('user_id', $userID)->orderBy('sequence')->get();
        #return response($formFields, 200);
    }
    /**
     * Get Particular formFields by ID
     */
    public function getFormFields($id)
    {
        if (FormFields::where('_id', $id)->exists()) {
            $formFields = FormFields::where('_id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($formFields, 200);
        } else {
            return response()->json([
                "message" => "FormFields not found"
            ], 404);
        }
    }
    /**
     * Create new formFields
     */
    public function createFormFields(Request $request)
    {
        $formFields = new FormFields;
        $formFields->address = $request->address;
        $formFields->city = $request->city;
        $formFields->delivery_time = $request->delivery_time;
        $formFields->pickup_time = $request->pickup_time;
        $formFields->phoneno = $request->phoneno;
        $formFields->user_id = $request->user_id;
        $formFields->created_by = $request->created_by;
        $formFields->save();

        return response()->json([
            "message" => "FormFields record created"
        ], 201);
    }

    /**
     * Update a formFields by ID
     */
    public function updateFormFields(Request $request, $id) {
        if (FormFields::where('id', $id)->exists()) {
          $formFields = FormFields::find($id);
  
          $formFields->address = is_null($request->address) ? $formFields->address : $formFields->address;
          $formFields->city = is_null($request->city) ? $formFields->city : $formFields->city;
          $formFields->delivery_time = is_null($request->delivery_time) ? $formFields->delivery_time : $formFields->delivery_time;
          $formFields->pickup_time = is_null($request->pickup_time) ? $formFields->pickup_time : $formFields->pickup_time;
          $formFields->phoneno = is_null($request->phoneno) ? $formFields->phoneno : $formFields->phoneno;
          $formFields->user_id = is_null($request->user_id) ? $formFields->user_id : $formFields->user_id;
          $formFields->created_by = is_null($request->created_by) ? $formFields->created_by : $formFields->created_by;
          $formFields->save();
  
          return response()->json([
            "message" => "records updated successfully"
          ], 200);
        } else {
          return response()->json([
            "message" => "FormFields not found"
          ], 404);
        }
      }

      /**
       * Delete a formFields by ID.
       */
      // public function deleteFormFields($id) {
      //   if(FormFields::where('id', $id)->exists()) {
            
      //     $formFields = FormFields::find($id);
      //     $formFields->delete();
  
      //     return response()->json([
      //       "message" => "records deleted"
      //     ], 202);
      //   } else {
      //     return response()->json([
      //       "message" => "FormFields not found"
      //     ], 404);
      //   }
      // }

      /**
       * Delete a product by ID.
       */
      public function deleteFormFields(FormFields $formField) {
        if ($formField->delete()) {
            return response()->json(["message" => "records deleted"], 200);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
      }
      public function updateSequence(Request $request){
        $fields = $request->get('fields');
        foreach($fields as $key=>$val){
          $field = FormFields::find($val['_id']);
          $field->sequence = $key;
          $field->save();
        }
      }
}
