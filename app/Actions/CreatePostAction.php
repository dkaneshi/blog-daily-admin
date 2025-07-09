<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

final class CreatePostAction
{
    public function execute(StorePostRequest $request): void
    {
        DB::transaction(function () use ($request): void {
            Post::create($request->validated());
        });
    }
}
