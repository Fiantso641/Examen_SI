<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'operator_name',
        'discount_percentage',
        'description',
        'start_date',
        'end_date',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActivePromotionByOperator($operatorName)
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('operator_name', $operatorName)
                    ->where('status', 'active')
                    ->groupStart()
                        ->where('start_date <=', $now)
                        ->where('end_date >=', $now)
                    ->groupEnd()
                    ->orWhere('start_date', null)
                    ->orWhere('end_date', null)
                    ->first();
    }

    public function getAllPromotions()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getActivePromotions()
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('status', 'active')
                    ->groupStart()
                        ->where('start_date <=', $now)
                        ->where('end_date >=', $now)
                    ->groupEnd()
                    ->orWhere('start_date', null)
                    ->orWhere('end_date', null)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function createPromotion($data)
    {
        return $this->insert($data);
    }

    public function updatePromotion($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deletePromotion($id)
    {
        return $this->delete($id);
    }

    public function getPromotionById($id)
    {
        return $this->find($id);
    }
}
