<?php

namespace App\Domain;

use App\Domain\PostsSource;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ApiPostsSource implements PostsSource
{
    private const PER_PAGE = 20;
    private const CACHE_TTL = 3600;
    private const LIMIT = 140;

    public function __construct(
        private readonly Client $client
    )
    {
    }

    public function posts(): Generator
    {
        $yielded = 0;
        for ($page = 1; $page++; ) {
            $cacheKey = implode('_', ['techcrunchPosts', self::PER_PAGE, $page]);
            $contents = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($page) {
                $response = $this->client->get('https://techcrunch.com/wp-json/wp/v2/posts', [
                    RequestOptions::QUERY => [
                        'per_page' => self::PER_PAGE,
                        'page' => $page,
                    ],
                    RequestOptions::HTTP_ERRORS => false,
                ]);
                if ($response->getStatusCode() !== 200)  {
                    return null;
                }
                return $response->getBody()->getContents();
            });
            $parsedResponse = json_decode($contents, true);
            if (!is_array($parsedResponse)) {
                return;
            }
            foreach ($parsedResponse as $post) {
                $cleanedContents = html_entity_decode(strip_tags($post['content']['rendered']));
                yield new Post($post['title']['rendered'], $cleanedContents);
                $yielded++;
            }
            if (count($parsedResponse) < self::PER_PAGE || $yielded >= self::LIMIT) {
                return;
            }
        }
    }
}
