<?php
namespace App\Services;

use App\Contracts\Repositories\BillRepository;
use App\Contracts\Services\BillContract;
use App\Helpers\UtilityHelper;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BillService implements BillContract
{
    private $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function reportBills(): array
    {
        try{
            $upcoming = [];
            $overdue = [];
            $paid = [];
            $payable = 0;
            $receivable = 0;
            $outstanding = 0;
            $bills = $this->billRepository->getAllBills(Auth::user()->id);
            date_default_timezone_set('Asia/Dhaka');
            foreach ($bills as $bill){
                $now = new DateTime();
                $now->format('Y-m-d H:i:s');
                $today = $now->getTimestamp();
                $due_date = strtotime($bill->due_date);
                $due_date_remaining = $today-$due_date;

                if($due_date_remaining < 0 && $bill->status == 0){
                    $upcoming[] = $bill;
                }elseif ($due_date_remaining > 0 && $bill->status == 0){
                    $overdue[] = $bill;
                }elseif ($bill->status == 1){
                    $paid[] = $bill;
                }

                if($bill->transaction_type == 'payable'){
                    $payable += $bill->amount;
                }else if($bill->transaction_type == 'receivable'){
                    $receivable += $bill->amount;
                }
            }
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Bill Report Fetched Successfully", [ 'upcoming' => $upcoming, 'overdue' => $overdue, 'paid' => $paid, 'payable' => $payable, 'receivable' => $receivable, 'outstanding' => $receivable - $payable ]);
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!!");
        }
    }

    public function getAllBills(): array
    {
        try {
            $bills = $this->billRepository->getAllBills(Auth::user()->id);
            return UtilityHelper::RETURN_SUCCESS_FORMAT(ResponseAlias::HTTP_OK, "Bill Fetched Successfully", $bills);
        }catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return UtilityHelper::RETURN_ERROR_FORMAT(ResponseAlias::HTTP_BAD_REQUEST, "Something went wrong!!!");
        }
    }

    public function createBill($request): array
    {
        try{
            $rules = [
                'currency' => 'required',
                'amount' => 'required|numeric',
                'payee' => 'required',
                'transaction_type' => 'required',
                'due_date' => 'required|date|after:1 day',
                'repeat_count' => 'required|numeric',
                'repeat_unit' => 'required',
                'category' => 'required',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    $validator->errors()
                );
            }
            $bill = [
                'currency' => $request->currency,
                'amount' => $request->amount,
                'payee' => $request->payee,
                'note' => $request->note,
                'transaction_type' => $request->transaction_type,
                'due_date' => $request->due_date,
                'repeat' => $request->repeat_count,
                'repeat_unit' => $request->repeat_unit,
                'status' => $request->status ? $request->status : 0,
                'notification' => $request->notification,
                'category_id' => $request->category,
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
                'amount' => 'required|numeric',
                'payee' => 'required',
                'due_date' => 'required|date|after:today',
                'repeat_count' => 'required|numeric',
                'repeat_unit' => 'required',
                'category' => 'required',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return UtilityHelper::RETURN_ERROR_FORMAT(
                    ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    $validator->errors()
                );
            }
            $bill = [
                'currency' => $request->currency,
                'amount' => $request->amount,
                'payee' => $request->payee,
                'note' => $request->note,
                'due_date' => $request->due_date,
                'transaction_type' => $request->transaction_type,
                'repeat' => $request->repeat_count,
                'repeat_unit' => $request->repeat_unit,
                'status' => $request->status,
                'category_id' => $request->category,
                'notification' => $request->notification,
                'user_id' => Auth::user()->id
            ];
            if ($this->billRepository->updateBill($request->id, $bill)) {
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

    public function checkNotification(): array
    {
        try{
            $unpaidBills = $this->billRepository->getUnpaidBills();
            $notifications = [];

            if($unpaidBills){
                foreach ($unpaidBills as $unpaidBill){
                    $now = new DateTime();
                    $now->format('Y-m-d H:i:s');
                    $today = $now->getTimestamp();
                    $due_date = strtotime($unpaidBill->due_date);
                    $due_date_remaining = $due_date-$today;
                    if($unpaidBill->repeat_unit == "don't" && $due_date_remaining >= 0 && ($due_date_remaining/60/60/24) <= $unpaidBill->notification){
                        $notifications[] = $unpaidBill;
                    }
                }
            }

            return $notifications;
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return [ 'error' => $exception->getMessage() ];
        }
    }
}
