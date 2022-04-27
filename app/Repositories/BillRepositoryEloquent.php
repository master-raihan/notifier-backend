<?php
namespace App\Repositories;

use App\Contracts\Repositories\BillRepository;
use App\Models\Bill;
use App\Repositories\BaseRepository\BaseRepository;

class BillRepositoryEloquent extends BaseRepository implements BillRepository
{
    public function model(): Bill
    {
        return new Bill();
    }

    public function getAllBills($userId)
    {
        return $this->model->where("user_id", $userId)->get();
    }

    public function createBill($bill)
    {
        return $this->model->create($bill);
    }

    public function updateBill($id, $bill)
    {
        return $this->model->find($id)->update($bill);
    }

    public function deleteBill($id)
    {
        return $this->model->find($id)->delete();
    }

    public function getBill($id)
    {
        return $this->model->find($id);
    }

    public function getUnpaidBills()
    {
        return $this->model->where("status", 0)->get();
    }
}
