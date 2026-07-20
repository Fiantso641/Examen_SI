<?php

namespace App\Models;

use CodeIgniter\Model;

class ValidPrefixModel extends Model
{
    protected $table = 'valid_prefixes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'prefix',
        'operator_name',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllValidPrefixes()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function isValidPrefix($phoneNumber)
    {
        $prefixes = $this->getAllValidPrefixes();
        foreach ($prefixes as $prefix) {
            if (strpos($phoneNumber, $prefix['prefix']) === 0) {
                return true;
            }
        }
        return false;
    }

    public function createPrefix($data)
    {
        return $this->insert($data);
    }

    public function updatePrefix($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deletePrefix($id)
    {
        return $this->delete($id);
    }
}
