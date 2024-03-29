<?php

namespace App\Services\Domain\Post;

use App\Models\Post;
use App\Services\Core\Post\PostService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

class UpdatePostService
{
    private PostService $postService;
    private Filesystem $filesystem;

    public function __construct(PostService $postService, Filesystem $filesystem)
    {
        $this->postService = $postService;
        $this->filesystem  = $filesystem;
    }

    public function update(Post $post, array $attributes, ?UploadedFile $thumbnail = null): bool
    {
        if ($thumbnail instanceof UploadedFile) {
            $this->filesystem->delete('public/posts_thumbnails/'.$post->getThumbnailFileName());
            $thumbnailFileName = $thumbnail->hashName();
            $thumbnail->storeAs(
                'public/posts_thumbnails',
                $thumbnailFileName
            );

            $attributes[Post::THUMBNAIL_FILE_NAME_COLUMN] = $thumbnailFileName;
        }

        $this->postService->update($post, $attributes);

        return true;
    }
}