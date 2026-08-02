<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user()->categories()->withCount('notes')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $request->user()->categories()->create($request->validated());

        return response()->json([
            'message' => 'Category berhasil dibuat.',
            'data' => $category->fresh()->loadCount('notes'),
        ], 201);
    }

    public function show(Request $request, int $category): JsonResponse
    {
        return response()->json([
            'data' => $this->findOwnedCategory($request, $category)->loadCount('notes'),
        ]);
    }

    public function update(UpdateCategoryRequest $request, int $category): JsonResponse
    {
        $categoryModel = $this->findOwnedCategory($request, $category);
        $categoryModel->update($request->validated());

        return response()->json([
            'message' => 'Category berhasil diperbarui.',
            'data' => $categoryModel->fresh()->loadCount('notes'),
        ]);
    }

    public function destroy(Request $request, int $category): JsonResponse
    {
        $categoryModel = $this->findOwnedCategory($request, $category);
        $categoryModel->delete();

        return response()->json([
            'message' => 'Category berhasil dihapus.',
        ]);
    }

    private function findOwnedCategory(Request $request, int $categoryId): Category
    {
        return $request->user()->categories()->findOrFail($categoryId);
    }
}
