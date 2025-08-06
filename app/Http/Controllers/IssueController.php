<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IssueController extends Controller
{
   public function index($projectId){
    $fullPath = storage_path('app/data/issues.json');

    if (!file_exists($fullPath)) {
        return response()->json([
            'message' => '404 file not found'
        ], 404);
    }

    $json = file_get_contents($fullPath);
    $issues = json_decode($json, true);

    // Filter all issues matching the projectId
    $filteredIssues = collect($issues)->where('project_id', (int) $projectId)->values();

    return response()->json([
        'success' => true,
        'issues' => $filteredIssues
    ], 200);
}



   public function getIssue($projectId, $issueId){
    $fullPath = storage_path('app/data/issues.json');

    if (!file_exists($fullPath)) {
        return response()->json([
            'message' => '404 file not found'
        ], 404);
    }

    $json = file_get_contents($fullPath);
    $issues = json_decode($json, true);

    $issue = collect($issues)
                ->where('project_id', (int) $projectId)
                ->firstWhere('id', (int) $issueId);

    if (!$issue) {
        return response()->json([
            'success' => false,
            'message' => 'Issue not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'issue' => $issue
    ]);
}

}
