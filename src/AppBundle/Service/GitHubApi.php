<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class GitHubApi
{
    public function getProfile($username)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.github.com/users/' . $username);
//        dump($response->getBody()->getContents());
//        exit();
        $data = json_decode($response->getBody()->getContents(), true);
        return [
            'avatar_url' => $data['avatar_url'],
            'name' => $data['name'],
            'login' => $data['login'],
            'details' => [
                'company' => $data['company'],
                'location' => $data['location'],
                'joined_on' => 'Joined on ' . (new \DateTime($data['created_at']))->format('d-m-Y'),
            ],
            'blog' => $data['blog'],
            'social_data' => [
                'Public Repos' => $data['public_repos'],
                'Followers' => $data['followers'],
                'Following' => $data['following'],
            ],
        ];
    }

    public function getRepos($username)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.github.com/users/' . $username . '/repos');
        $data = json_decode($response->getBody()->getContents(), true);
//        dump($data);
//        exit();
        return [
            'repo_count' => count($data),
            'most_stars' => array_reduce($data, function ($mostStars, $currentRepo) {
//                dump($mostStars);
//                dump($currentRepo);
//                exit();
                return $currentRepo['stargazers_count'] > $mostStars ? $currentRepo['stargazers_count'] : $mostStars;
            }, 0),
            'repos' => $data,
        ];
    }
}