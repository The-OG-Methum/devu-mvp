<?php

namespace App\Http\Controllers;

use App\Models\Preferences;
use App\Models\Project;
use App\Models\User;
use Auth;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */ //6|DjGklgyMymaydHzVbtr72an6uEVXpIb32d3OtWuEadfb65fe
   public function index()
{

    $response = Http::get('https://api.github.com/search/repositories?q=is:public&sort=stars&order=desc&per_page=10');


    

   
    return response()->json([
        'success'=>true,
        'projects'=>$response->json()
    ]);

     // Github Api
/*
*/

}


   public function getProject($id){

        $fullPath = storage_path('app/data/repos.json');
        
         if (!file_exists($fullPath)) {
        return response()->json([
            'message' => '404 file not found'
        ], 404);
        }

        $json = file_get_contents($fullPath);
        $projects = json_decode($json,true);

        $project = collect($projects)->firstWhere('id', (int) $id);

        if(!$project){

            return response()->json([
                'message'=>'Repo not found'
            ],404);
        }


        return response()->json([
            'success'=>true,
            'project'=> $project
        ]);





   }

 public function projects()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $preference = $user->preferences; 

    if (!$preference) {
        return response()->json([
            'success' => false,
            'message' => 'Preferences not found'
        ], 404);
    }

    $languages = $preference->languages;
    $interests = $preference->interests;

    $languageNames = $languages->pluck('name')->toArray();
    $interestsNames = $interests->pluck('name')->toArray();

    $languageQurey = collect($languageNames)->map(fn($lang) => 'language:$lang')->implode('+');

    $interestQuery = collect($interestsNames)->map(fn($tag) => 'topic:$tag')->implode('+');

    $finalQuery = $languageQurey . '+' . $interestQuery . '+is:public'. '+stars:>10';

    $query = urlencode($finalQuery);
    
    $response = Http::get('https://api.github.com/search/repositories?q=$query');

    
    return response()->json([
        'success' => true,
        'projects'=>$response->json()
    ], 200);

    


}

}
