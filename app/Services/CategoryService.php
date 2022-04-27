<?php
namespace App\Services;

use App\Contracts\Repositories\CategoryRepository;
use App\Contracts\Services\CategoryContract;
use App\Helpers\UtilityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryService implements CategoryContract
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(): array
    {
        try {
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Category Fetched Successfully", $this->categoryRepository->getAllCategories());
        }catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!!");
        }
    }

    public function createCategory($request): array
    {
        try{
            $rules = [
                'category_name' => 'required',
                'transaction_type' => 'required',
                'description' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    $validator->errors()
                );
            }
            $category = [
                'category_name' => $request->category_name,
                'transaction_type' => $request->transaction_type,
                'description' => $request->description,
                'parent_id' => $request->parent_category
            ];

            $categoryData = $this->categoryRepository->createCategory($category);

            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "New Category Created", $categoryData);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function updateCategory($id, $request): array
    {
        try {
            $rules = [
                'category_name' => 'required',
                'transaction_type' => 'required',
                'description' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    $validator->errors()
                );
            }
            $category = [
                'category_name' => $request->category_name,
                'transaction_type' => $request->transaction_type,
                'description' => $request->description,
                'parent_id' => $request->parent_category
            ];

            if ($this->categoryRepository->updateCategory($category, $request->id)) {
                $updatedCategory = $this->categoryRepository->getCategory($request->id);
                return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, 'Category Updated Successfully!', $updatedCategory);
            }

            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, 'Failed To Update Category!');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function deleteCategory($id): array
    {
        try{
            if($this->categoryRepository->deleteCategory($id)){
                return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, 'Category Successfully Deleted',[]);
            }
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, 'Failed To Delete Category',[]);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function getCategory($id): array
    {
        try{
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Category Details", $this->categoryRepository->getCategory($id));
        }catch (\Exception $exception){
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }
}
