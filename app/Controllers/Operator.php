<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ValidPrefixModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;
use App\Models\FeeScheduleModel;
use App\Models\OperatorConfigModel;

class Operator extends BaseController
{
    protected $clientModel;
    protected $validPrefixModel;
    protected $transactionModel;
    protected $operationTypeModel;
    protected $feeScheduleModel;
    protected $operatorConfigModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->validPrefixModel = new ValidPrefixModel();
        $this->transactionModel = new TransactionModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeScheduleModel = new FeeScheduleModel();
        $this->operatorConfigModel = new OperatorConfigModel();
    }

    public function dashboard()
    {
        $data = [
            'total_clients' => count($this->clientModel->getAllClients()),
            'total_balance' => $this->clientModel->getTotalBalance(),
            'total_transactions' => count($this->transactionModel->getAllTransactions()),
            'total_fees' => $this->transactionModel->getTotalFees(),
            'valid_prefixes' => $this->validPrefixModel->getAllValidPrefixes()
        ];

        return view('operator/dashboard', $data);
    }

    public function prefixes()
    {
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');
            
            if ($action === 'add') {
                $data = [
                    'prefix' => $this->request->getPost('prefix'),
                    'operator_name' => $this->request->getPost('operator_name'),
                    'status' => 'active'
                ];
                $this->validPrefixModel->createPrefix($data);
                return redirect()->to('operator/prefixes')->with('success', 'Préfixe ajouté avec succès');
            } elseif ($action === 'delete') {
                $id = $this->request->getPost('id');
                $this->validPrefixModel->deletePrefix($id);
                return redirect()->to('operator/prefixes')->with('success', 'Préfixe supprimé avec succès');
            }
        }

        $data = [
            'prefixes' => $this->validPrefixModel->getAllValidPrefixes()
        ];

        return view('operator/prefixes', $data);
    }

    public function prefixes_edit($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'prefix' => $this->request->getPost('prefix'),
                'operator_name' => $this->request->getPost('operator_name'),
                'status' => $this->request->getPost('status') ?? 'active'
            ];
            $this->validPrefixModel->updatePrefix($id, $data);
            return redirect()->to('operator/prefixes')->with('success', 'Préfixe mis à jour avec succès');
        }

        $prefix = $this->validPrefixModel->find($id);
        if (!$prefix) {
            return redirect()->to('operator/prefixes')->with('error', 'Préfixe introuvable');
        }

        return view('operator/prefixes_edit', ['prefix' => $prefix]);
    }

    public function operations_edit($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status') ?? 'active'
            ];
            $this->operationTypeModel->updateOperationType($id, $data);
            return redirect()->to('operator/operations')->with('success', 'Type d\'opération mis à jour avec succès');
        }

        $operation = $this->operationTypeModel->find($id);
        if (!$operation) {
            return redirect()->to('operator/operations')->with('error', 'Type d\'opération introuvable');
        }

        return view('operator/operations_edit', ['operation' => $operation]);
    }

    public function operations()
    {
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');
            
            if ($action === 'add') {
                $data = [
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'status' => 'active'
                ];
                $this->operationTypeModel->createOperationType($data);
                return redirect()->to('operator/operations')->with('success', 'Type d\'opération ajouté avec succès');
            } elseif ($action === 'delete') {
                $id = $this->request->getPost('id');
                $this->operationTypeModel->updateOperationType($id, ['status' => 'inactive']);
                return redirect()->to('operator/operations')->with('success', 'Type d\'opération désactivé avec succès');
            }
        }

        $data = [
            'operations' => $this->operationTypeModel->getAllOperationTypes()
        ];

        return view('operator/operations', $data);
    }

    public function config()
    {
        log_message('info', 'operator::config method: ' . $this->request->getMethod() . ' POST data: ' . json_encode($_POST));
        if ($this->request->getMethod() === 'post' || $this->request->is('post') || !empty($_POST)) {
            $value = $this->request->getPost('transfer_external_surcharge_percent');
            // Ignore empty submissions to avoid overwriting with 0.00 from repeated empty POSTs
            if ($value === null || $value === '') {
                log_message('warning', 'operator::config empty value, skipping setConfig');
            } else {
                log_message('info', 'operator::config about to call setConfig');
                try {
                    $res = $this->operatorConfigModel->setConfig('transfer_external_surcharge_percent', number_format((float)$value, 2, '.', ''), 'Pourcentage additionnel pour les transferts vers les autres opérateurs');
                    log_message('info', 'operator::config setConfig result: ' . var_export($res, true));
                } catch (\Throwable $e) {
                    log_message('error', 'operator::config setConfig exception: ' . $e->getMessage());
                    log_message('error', $e->getTraceAsString());
                }
            }
            return redirect()->to('operator/config')->with('success', 'Configuration enregistrée avec succès');
        }

        $data = [
            'external_surcharge_percent' => $this->operatorConfigModel->getConfig('transfer_external_surcharge_percent') ?? '0.00'
        ];

        return view('operator/config', $data);
    }

    public function fees()
    {
        // Log pour débogage
        log_message('info', 'Méthode: ' . $this->request->getMethod());
        log_message('info', 'POST data: ' . json_encode($_POST));
        
        if ($this->request->getMethod() === 'post' || $this->request->is('post') || !empty($_POST)) {
            $action = $this->request->getPost('action');
            
            log_message('info', 'Action: ' . $action);
            
            if ($action === 'add') {
                $data = [
                    'operation_type_id' => (int)$this->request->getPost('operation_type_id'),
                    'min_amount' => (float)$this->request->getPost('min_amount'),
                    'max_amount' => (float)$this->request->getPost('max_amount'),
                    'fee_amount' => (float)$this->request->getPost('fee_amount'),
                    'fee_percentage' => (float)$this->request->getPost('fee_percentage')
                ];
                log_message('info', 'Données add: ' . json_encode($data));
                $result = $this->feeScheduleModel->createFeeSchedule($data);
                log_message('info', 'Résultat création: ' . var_export($result, true));
                return redirect()->to('operator/fees')->with('success', 'Barème de frais ajouté avec succès');
            } elseif ($action === 'delete') {
                $id = $this->request->getPost('id');
                log_message('info', 'ID delete: ' . $id);
                $result = $this->feeScheduleModel->deleteFeeSchedule($id);
                log_message('info', 'Résultat suppression: ' . var_export($result, true));
                return redirect()->to('operator/fees')->with('success', 'Barème de frais supprimé avec succès');
            }
        }

        $data = [
            'fee_schedules' => $this->feeScheduleModel->getAllFeeSchedules(),
            'operations' => $this->operationTypeModel->getAllOperationTypes()
        ];

        return view('operator/fees', $data);
    }

    public function fees_edit($id)
    {
        // Log pour débogage
        log_message('info', 'fees_edit - Méthode: ' . $this->request->getMethod());
        log_message('info', 'fees_edit - POST data: ' . json_encode($_POST));
        
        if ($this->request->getMethod() === 'post' || $this->request->is('post') || !empty($_POST)) {
            $data = [
                'operation_type_id' => (int)$this->request->getPost('operation_type_id'),
                'min_amount' => (float)$this->request->getPost('min_amount'),
                'max_amount' => (float)$this->request->getPost('max_amount'),
                'fee_amount' => (float)$this->request->getPost('fee_amount'),
                'fee_percentage' => (float)$this->request->getPost('fee_percentage')
            ];
            
            log_message('info', 'fees_edit - Données update: ' . json_encode($data));
            $result = $this->feeScheduleModel->updateFeeSchedule($id, $data);
            log_message('info', 'fees_edit - Résultat update: ' . var_export($result, true));
            
            return redirect()->to('operator/fees')->with('success', 'Barème de frais mis à jour avec succès');
        }

        $data = [
            'fee_schedule' => $this->feeScheduleModel->find($id),
            'operations' => $this->operationTypeModel->getAllOperationTypes()
        ];

        return view('operator/fees_edit', $data);
    }

    public function clients()
    {
        $data = [
            'clients' => $this->clientModel->getAllClients(),
            'total_balance' => $this->clientModel->getTotalBalance()
        ];

        return view('operator/clients', $data);
    }

    public function transactions()
    {
        $data = [
            'transactions' => $this->transactionModel->getAllTransactions()
        ];

        return view('operator/transactions', $data);
    }

    public function reports()
    {
        $operations = $this->operationTypeModel->getAllOperationTypes();
        $reportData = [];
        $transferType = $this->operationTypeModel->getOperationTypeByCode('TRANSFER');
        $internalFees = 0;
        $externalFees = 0;
        $amountsByOperator = [];
        $totalInternalAmount = 0;
        $totalExternalAmount = 0;

        foreach ($operations as $operation) {
            $reportData[] = [
                'operation' => $operation,
                'total_fees' => $this->transactionModel->getTotalFeesByOperationType($operation['id'])
            ];
        }

        if ($transferType) {
            $transfers = $this->transactionModel->getTransactionsByOperationType($transferType['id']);

            foreach ($transfers as $transaction) {
                $sender = $this->clientModel->find($transaction['client_id']);
                $senderOperator = $sender ? $this->validPrefixModel->getOperatorByPhone($sender['phone_number']) : null;
                $recipientOperator = $this->validPrefixModel->getOperatorByPhone($transaction['recipient_phone']);
                $isSameOperator = $senderOperator && $recipientOperator && $senderOperator === $recipientOperator;

                if ($isSameOperator) {
                    $internalFees += $transaction['fee'];
                    $totalInternalAmount += $transaction['amount'];
                } else {
                    $externalFees += $transaction['fee'];
                    $totalExternalAmount += $transaction['amount'];
                }

                $operatorName = $recipientOperator ?: 'Autre opérateur';
                if (!isset($amountsByOperator[$operatorName])) {
                    $amountsByOperator[$operatorName] = 0;
                }
                $amountsByOperator[$operatorName] += $transaction['amount'];
            }
        }

        $data = [
            'report_data' => $reportData,
            'total_fees' => $this->transactionModel->getTotalFees(),
            'total_balance' => $this->clientModel->getTotalBalance(),
            'internal_fees' => $internalFees,
            'external_fees' => $externalFees,
            'total_internal_amount' => $totalInternalAmount,
            'total_external_amount' => $totalExternalAmount,
            'amounts_by_operator' => $amountsByOperator
        ];

        return view('operator/reports', $data);
    }
}
