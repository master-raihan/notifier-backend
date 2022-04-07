<?php
namespace App\Services;

use App\Contracts\Repositories\BillRepository;
use App\Contracts\Services\BillContract;
use App\Helpers\UtilityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BillService implements BillContract
{
    private $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function getAllBills(): array
    {
        try {
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Bill Fetched Successfully", $this->billRepository->getAllBills(Auth::user()->id));
        }catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!!");
        }
    }

    public function createBill($request): array
    {
        try{
            $rules = [
                'amount' => 'required',
                'payee' => 'required',
                'note' => 'required',
                'due_date' => 'required|date',
                'repeat' => 'required',
                'category_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }
            $bill = [
                'amount' => $request->amount,
                'payee' => $request->payee,
                'note' => $request->note,
                'due_date' => $request->due_date,
                'repeat' => $request->repeat,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'user_id' => Auth::user()->id
            ];

            $billData = $this->billRepository->createBill($bill);

            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "New Bill Created", $billData);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function updateBill($id, $request): array
    {
        try {
            $rules = [
                'amount' => 'required',
                'payee' => 'required',
                'note' => 'required',
                'due_date' => 'required|date',
                'repeat' => 'required',
                'category_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }
            $bill = [
                'amount' => $request->amount,
                'payee' => $request->payee,
                'note' => $request->note,
                'due_date' => $request->due_date,
                'repeat' => $request->repeat,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'user_id' => Auth::user()->id
            ];

            if ($this->billRepository->updateBill($bill, $request->id)) {
                $updatedBill = $this->billRepository->getBill($request->id);
                return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, 'Bill Updated Successfully!', $updatedBill);
            }

            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, 'Failed To Update Bill!');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function deleteBill($id): array
    {
        try{
            if($this->billRepository->deleteBill($id)){
                return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, 'Bill Successfully Deleted',[]);
            }
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, 'Failed To Delete Bill',[]);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }

    public function getBill($id): array
    {
        try{
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Bill Details", $this->billRepository->getBill($id));
        }catch (\Exception $exception){
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!");
        }
    }
}
