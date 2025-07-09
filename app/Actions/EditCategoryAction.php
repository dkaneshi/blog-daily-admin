<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

final class EditCategoryAction
{
    public function execute(UpdateCategoryRequest $request, Category $category): void
    {
        DB::transaction(function () use ($request, $category): void {
            $category->update($request->validated());
        });
    }
}
