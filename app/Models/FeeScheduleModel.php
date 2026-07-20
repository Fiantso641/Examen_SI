<?php

namespace App\Models;

use CodeIgniter\Model;

class FeeScheduleModel extends Model
{
    protected $table = 'fee_schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'operation_type_id',
        'min_amount',
        'max_amount',
        'fee_amount',
        'fee_percentage'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getFeeForAmount($operationTypeId, $amount)
    {
        return $this->where('operation_type_id', $operationTypeId)
                    ->where('min_amount <=', $amount)
                    ->where('max_amount >=', $amount)
                    ->first();
    }

    public function getFeesByOperationType($operationTypeId)
    {
        return $this->where('operation_type_id', $operationTypeId)
                    ->orderBy('min_amount', 'ASC')
                    ->findAll();
    }

    public function createFeeSchedule($data)
    {
        return $this->insert($data);
    }

    public function updateFeeSchedule($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteFeeSchedule($id)
    {
        return $this->delete($id);
    }

    public function getAllFeeSchedules()
    {
        return $this->select('fee_schedules.*, operation_types.code as operation_code, operation_types.name as operation_name')
                    ->join('operation_types', 'operation_types.id = fee_schedules.operation_type_id')
                    ->orderBy('fee_schedules.operation_type_id, fee_schedules.min_amount')
                    ->findAll();
    }
}
