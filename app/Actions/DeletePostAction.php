<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

final class DeletePostAction
{
    public function execute(Post $post): void
    {
        DB::transaction(function () use ($post): void {
            $post->delete();
        });
    }
}
