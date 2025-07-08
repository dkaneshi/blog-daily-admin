<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

final class EditPostAction
{
    public function execute(UpdatePostRequest $request, Post $post): void
    {
        DB::transaction(function () use ($request, $post) {
            $post->update($request->validated());
        });
    }
}
