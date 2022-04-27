<?php
namespace App\Contracts\Services;

interface BillContract
{
    public function getAllBills();
    public function reportBills();
    public function createBill($request);
    public function updateBill($id, $request);
    public function deleteBill($id);
    public function getBill($id);
    public function checkNotification();
}
