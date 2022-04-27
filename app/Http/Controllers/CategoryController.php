<?php
namespace App\Http\Controllers;

use App\Contracts\Services\CategoryContract;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryContract $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getAllCategories(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->categoryService->getAllCategories();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function createCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->categoryService->createCategory($request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function updateCategory($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->categoryService->updateCategory($id, $request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function deleteCategory($id): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->categoryService->deleteCategory($id);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function getCategory($id): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->categoryService->getCategory($id);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }
}
