<?php
namespace App\Contracts\Repositories;

interface BillRepository
{
    public function getAllBills($userId);
    public function getUnpaidBills();
    public function createBill($bill);
    public function updateBill($id, $bill);
    public function deleteBill($id);
    public function getBill($id);
}
