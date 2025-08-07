<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Http;
use App\Models\Project;
use Carbon\Carbon;

class FetchGitHubRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:github-projects';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch GitHub repositories and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $languages = ['javascript', 'python', 'java', 'php']; // You can add more
        $topic = 'web-development'; // You can mix topics later

        foreach ($languages as $language) {
            $query = "language:$language topic:$topic stars:>50";
            $url = "https://api.github.com/search/repositories?q=" . urlencode($query) . "&sort=stars&order=desc&per_page=5";

            $response = Http::get($url);

            if ($response->ok()) {
                foreach ($response['items'] as $repo) {
                    Project::updateOrCreate(
                        ['repository_url' => $repo['html_url']],
                        [
                            'name' => $repo['name'],
                            'description' => $repo['description'],
                            'long_description' => $repo['description'],
                            'tech_stack' => [$language],
                            'repository_url' => $repo['html_url'],
                            'website_url' => $repo['homepage'] ?? null,
                            'stars' => $repo['stargazers_count'],
                            'open_issues_count' => $repo['open_issues_count'],
                            'contributors_count' => 0, // Optional: Fetch separately
                            'last_updated' => Carbon::parse($repo['updated_at']),
                            'difficulty' => 'intermediate', // Placeholder
                            'codebase_overview' => [], // Optional: Generate manually
                            'contribution_guide' => [] // Optional: Generate manually
                        ]
                    );
                }
                $this->info("Fetched for language: $language");
            } else {
                $this->error("GitHub API error for $language: " . $response->body());
            }

            sleep(2); // Avoid rate limits
        }
    }
}
