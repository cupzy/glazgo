<?php

namespace App\Http\Actions;

use App\Domain\PostsExporter;
use App\Domain\PostsSourceFactory;
use App\Http\Requests\GetPostsRequest;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetPostsAction
{
    private const DEFAULT_RESPONSE_FORMAT = 'xlsx';

    public function __construct(
        private readonly PostsExporter $exporter,
        private readonly PostsSourceFactory $postsSourceFactory,
    )
    {
    }

    public function __invoke(GetPostsRequest $request): BinaryFileResponse
    {
        $requestData = $request->validated();

        $format = $requestData['format'] ?? self::DEFAULT_RESPONSE_FORMAT;
        if (key_exists('file', $requestData)) {
            $source = $this->postsSourceFactory->getFileSource($requestData['file']);
        } else {
            $source = $this->postsSourceFactory->getApiSource();
        }

        $file = $this->exporter->export($source, $format);

        return Response::file($file);
    }
}
