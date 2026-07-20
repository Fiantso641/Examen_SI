<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'phone_number',
        'pin',
        'full_name',
        'balance',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getClientByPhone($phone)
    {
        return $this->where('phone_number', $phone)->first();
    }

    public function createClient($data)
    {
        return $this->insert($data);
    }

    public function updateBalance($clientId, $amount)
    {
        return $this->set('balance', 'balance + ' . $amount, false)
                    ->where('id', $clientId)
                    ->update();
    }

    public function deductBalance($clientId, $amount)
    {
        return $this->set('balance', 'balance - ' . $amount, false)
                    ->where('id', $clientId)
                    ->update();
    }

    public function getAllClients()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getTotalBalance()
    {
        $result = $this->selectSum('balance')->first();
        return $result['balance'] ?? 0;
    }
}
