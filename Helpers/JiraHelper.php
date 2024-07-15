<?php

declare(strict_types=1);

namespace Modules\Ticket\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait JiraHelper
{
    public function connectToJira(string $host, string $username, string $token): ?Client
    {
        return new Client([
            'base_uri' => $host,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($username.':'.$token),
            ],
        ]);
    }

    public function getJiraProjects(Client $client): ?array
    {
        try {
            $response = $client->get('/rest/api/2/project');

            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            Log::error($e->getTraceAsString());

            return null;
        }
    }

    public function getJiraTicketsByProject(Client $client, $projectKeys): ?array
    {
        try {
            $formatIssues = function ($issues) {
                $results = [];
                foreach ($issues as $issue) {
                    $results[] = [
                        'code' => $issue->key,
                        'name' => $issue->fields->summary,
                        'data' => $issue,
                    ];
                }

                return $results;
            };
            $results = [];
            foreach ($projectKeys as $projectKey) {
                $response = $client->get('/rest/api/2/search?jql=project='.$projectKey);
                $data = json_decode($response->getBody()->getContents());
                $results[$projectKey] = [
                    'total' => $data->total,
                    'issues' => $formatIssues($data->issues),
                ];
            }

            return $results;
        } catch (GuzzleException $e) {
            Log::error($e->getTraceAsString());

            return null;
        }
    }

    public function getJiraTicketDetails($host, $username, $token, $url)
    {
        try {
            $client = $this->connectToJira($host, $username, $token);
            $url = explode('/', $url);
            $response = $client->get('/rest/api/2/issue/'.$url[sizeof($url) - 1]);

            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            Log::error($e->getTraceAsString());

            return null;
        }
    }
}
