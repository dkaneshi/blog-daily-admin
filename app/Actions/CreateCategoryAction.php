<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

final class CreateCategoryAction
{
    public function execute(StoreCategoryRequest $request): void
    {
        DB::transaction(function () use ($request): void {
            Category::create($request->validated());
        });
    }
}
