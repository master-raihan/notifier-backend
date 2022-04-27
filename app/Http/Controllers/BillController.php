<?php
namespace App\Http\Controllers;

use App\Contracts\Services\BillContract;
use App\Helpers\UtilityHelper;
use App\Models\DailyReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    private $billService;

    public function __construct(BillContract $billService)
    {
        $this->billService = $billService;
    }

    public function reportBills(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->reportBills();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function getAllBills(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->getAllBills();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function createBill(Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->createBill($request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function updateBill($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->updateBill($id, $request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function deleteBill($id): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->deleteBill($id);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function getBill($id): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->billService->getBill($id);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function getNotifications(): \Illuminate\Http\JsonResponse
    {
        return response()->json(UtilityHelper::RETURN_SUCCESS_FORMAT(
            200,
            'New Notifications',
            DailyReminder::where('user_id', Auth::user()->id)->get(),
        ), 200);
    }
}
