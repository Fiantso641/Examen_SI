<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'transaction_id',
        'operation_type_id',
        'client_id',
        'recipient_phone',
        'amount',
        'fee',
        'balance_before',
        'balance_after',
        'description',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createTransaction($data)
    {
        return $this->insert($data);
    }

    public function getTransactionsByClient($clientId)
    {
        return $this->where('client_id', $clientId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getTransactionById($transactionId)
    {
        return $this->where('transaction_id', $transactionId)->first();
    }

    public function getAllTransactions()
    {
        return $this->select('transactions.*, clients.phone_number, clients.full_name, operation_types.name as operation_name')
                    ->join('clients', 'clients.id = transactions.client_id')
                    ->join('operation_types', 'operation_types.id = transactions.operation_type_id')
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll();
    }

    public function getTotalFeesByOperationType($operationTypeId)
    {
        $result = $this->selectSum('fee')
                    ->where('operation_type_id', $operationTypeId)
                    ->first();
        return $result['fee'] ?? 0;
    }

    public function getTotalFees()
    {
        $result = $this->selectSum('fee')->first();
        return $result['fee'] ?? 0;
    }
}
