<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateCategoryAction;
use App\Actions\DeleteCategoryAction;
use App\Actions\EditCategoryAction;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;

final class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
    {
        $action->execute($request);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, EditCategoryAction $action, Category $category)
    {
        $action->execute($request, $category);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCategoryAction $action, Category $category)
    {
        try {
            $action->execute($category);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1451 ||
                str_contains($e->getMessage(), 'foreign key constraint fails')
            ) {
                return redirect()
                    ->back()
                    ->with('error', 'Category cannot be deleted because it has associated posts. Please delete or reassign the posts first.');
            }

            throw $e;
        }
    }
}
