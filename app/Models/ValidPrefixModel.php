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

    protected function normalizePhone($phone)
    {
        return preg_replace('/\D+/', '', trim($phone));
    }

    public function findPrefixByPhone($phoneNumber)
    {
        $phoneNumber = $this->normalizePhone($phoneNumber);
        $prefixes = $this->getAllValidPrefixes();
        usort($prefixes, function ($a, $b) {
            return strlen($b['prefix']) - strlen($a['prefix']);
        });

        foreach ($prefixes as $prefix) {
            if (strpos($phoneNumber, $prefix['prefix']) === 0) {
                return $prefix;
            }
        }

        return null;
    }

    public function isValidPrefix($phoneNumber)
    {
        return $this->findPrefixByPhone($phoneNumber) !== null;
    }

    public function getOperatorByPhone($phoneNumber)
    {
        $prefix = $this->findPrefixByPhone($phoneNumber);
        return $prefix ? $prefix['operator_name'] : null;
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
