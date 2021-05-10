<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use File;
use App\Models\Translation;

class TransaltionController extends Controller
{

  public function translation(Request $request) {
      $language = $request->language ?? 'en';

      $translation = Translation::where('language',$language)->get();

      return response()->json(["translation" => $translation], 200);
  }
  /**
   * Get all the translation from the database table.
   */
  public function getAllTranslation(Request $request)
  {
    $minutes = 24 * 60;
    $directories = array(Lang::getLocale());
    $collection = new \stdClass;
    foreach ($directories as $directory) {
      $path = resource_path('lang/' . $directory);
      $allTranslations = collect(File::allFiles($path))->flatMap(function ($file) use ($directory) {
        return [
          ($translation = $file->getBasename('.php')) => trans($translation, array(), null, $directory),
        ];
      });
      
      // $collection->$directory = $allTranslations;
    }
    return response()->json($allTranslations, 200);
  }

  /**
   * Get Current Locale
   */
  public function getCurrentLocale($id)
  {

    $locale = Lang::getLocale();
    return response(['locale' => $locale], 200);
  }
}
