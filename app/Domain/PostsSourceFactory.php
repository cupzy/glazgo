<?php

namespace App\Domain;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class PostsSourceFactory
{
    public function __construct(
        private readonly Client $client
    )
    {
    }

    public function getApiSource(): PostsSource
    {
        return new ApiPostsSource($this->client);
    }

    public function getFileSource(UploadedFile $file): PostsSource
    {
        return new FilePostsSource($file);
    }
}
