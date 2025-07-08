<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

final class DeleteCategoryAction
{
    public function execute(Category $category): void
    {
        DB::transaction(function () use ($category) {
            $category->delete();
        });
    }
}
