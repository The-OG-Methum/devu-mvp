<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Preferences;
use App\Models\Languages;
use App\Models\Interests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserPreferencesController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'preferred_languages' => 'required|array',
        'areas_of_interest' => 'required|array',
        'experience_level' => 'required|in:beginner,intermediate,experienced',
    ]);

    $user = auth()->user();

    // Get all valid languages and interests from DB
    $validLanguages = Languages::pluck('name')->mapWithKeys(fn($name) => [strtolower($name) => $name]);
    $validInterests = Interests::pluck('name')->mapWithKeys(fn($name) => [strtolower($name) => $name]);

    // Map frontend input to proper casing based on DB
    $preferredLanguages = collect($request->input('preferred_languages'))
        ->map(fn($lang) => $validLanguages[strtolower($lang)] ?? null)
        ->filter()
        ->values()
        ->toArray();

    $areasOfInterest = collect($request->input('areas_of_interest'))
        ->map(fn($interest) => $validInterests[strtolower($interest)] ?? null)
        ->filter()
        ->values()
        ->toArray();

    // Handle invalids
    if (count($preferredLanguages) !== count($request->preferred_languages)) {
        return response()->json(['message' => 'One or more languages are invalid.'], 422);
    }

    if (count($areasOfInterest) !== count($request->areas_of_interest)) {
        return response()->json(['message' => 'One or more interests are invalid.'], 422);
    }

    // Create or update preferences
    $preference = Preferences::updateOrCreate(
        ['user_id' => $user->id],
        ['experience_level' => strtolower($request->experience_level)]
    );

    // Sync pivot tables
    $languageIds = Languages::whereIn('name', $preferredLanguages)->pluck('id')->toArray();
    $interestIds = Interests::whereIn('name', $areasOfInterest)->pluck('id')->toArray();

    $preference->languages()->sync($languageIds);
    $preference->interests()->sync($interestIds);

    return response()->json(['message' => 'Preferences saved successfully.']);
}

    public function show()
    {
        $user = Auth::user();
        $preferences = $user->preferences()->with(['languages', 'interests'])->first();

        return response()->json($preferences);
    }
}
