<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationTypeModel extends Model
{
    protected $table = 'operation_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code',
        'name',
        'description',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOperationTypeByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    public function getAllOperationTypes()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function createOperationType($data)
    {
        return $this->insert($data);
    }

    public function updateOperationType($id, $data)
    {
        return $this->update($id, $data);
    }
}
