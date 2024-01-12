<?php

namespace App\Domain;

use Generator;

interface PostsSource
{
    /**
     * @return Generator<Post>
     */
    public function posts(): Generator;
}
