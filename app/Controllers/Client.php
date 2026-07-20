<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ValidPrefixModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;
use App\Models\FeeScheduleModel;
use App\Models\OperatorConfigModel;

class Client extends BaseController
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

    public function login()
    {
        if ($this->request->getMethod() === 'post' || $this->request->is('post')) {
            $phone = $this->request->getPost('phone');
            $pin = $this->request->getPost('pin');

            // Vérifier si le préfixe est valide
            if (!$this->validPrefixModel->isValidPrefix($phone)) {
                return redirect()->to('client/login')->with('error', 'Numéro de téléphone non valide (préfixe non reconnu)');
            }

            // Chercher le client
            $client = $this->clientModel->getClientByPhone($phone);

            // Si le client n'existe pas, le créer automatiquement
            if (!$client) {
                $newClient = [
                    'phone_number' => $phone,
                    'pin' => $pin,
                    'full_name' => 'Client ' . $phone,
                    'balance' => 0.00,
                    'status' => 'active'
                ];
                $clientId = $this->clientModel->createClient($newClient);
                $client = $this->clientModel->find($clientId);
            } else {
                // Vérifier le PIN
                if ($client['pin'] !== $pin) {
                    return redirect()->to('client/login')->with('error', 'PIN incorrect');
                }
            }

            // Créer la session
            session()->set([
                'client_id' => $client['id'],
                'phone_number' => $client['phone_number'],
                'full_name' => $client['full_name'],
                'balance' => $client['balance'],
                'logged_in' => true
            ]);

            // Afficher directement le dashboard au lieu de rediriger
            $clientId = session()->get('client_id');
            $client = $this->clientModel->find($clientId);
            
            // Mettre à jour le solde dans la session
            session()->set('balance', $client['balance']);

            $data = [
                'client' => $client,
                'recent_transactions' => $this->transactionModel->getTransactionsByClient($clientId)
            ];

            return view('client/dashboard', $data);
        }

        return view('client/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('client/login');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);
        
        // Mettre à jour le solde dans la session
        session()->set('balance', $client['balance']);

        $data = [
            'client' => $client,
            'recent_transactions' => $this->transactionModel->getTransactionsByClient($clientId)
        ];

        return view('client/dashboard', $data);
    }

    public function balance()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);
        
        session()->set('balance', $client['balance']);

        return view('client/balance', ['client' => $client]);
    }

    public function deposit()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        // Debug: log request method and raw input for troubleshooting (writable/)
        file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " METHOD: " . $this->request->getMethod() . "\nBODY: " . file_get_contents('php://input') . "\n\n", FILE_APPEND);

        if ($this->request->getMethod() === 'post' || $this->request->is('post')) {
            file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " ENTER_POST\n", FILE_APPEND);
            $amount = (float)$this->request->getPost('amount');
            if ($amount <= 0) {
                file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " INVALID_AMOUNT: $amount\n", FILE_APPEND);
                return redirect()->to('client/deposit')->with('error', 'Montant invalide');
            }
            $clientId = session()->get('client_id');
            
            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];
            // Debug: record POST entry
            @file_put_contents(WRITEPATH . 'debug.log', date('c') . " DEPOSIT POST from client=" . session()->get('client_id') . " amount=" . $this->request->getPost('amount') . "\n", FILE_APPEND);

            // Calculer les frais (dépôt = gratuit)
            $fee = 0;
            $balanceAfter = $balanceBefore + $amount;

            // Mettre à jour le solde
            file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " UPDATE_BALANCE: client=$clientId amount=$amount\n", FILE_APPEND);
            $this->clientModel->updateBalance($clientId, $amount);

            // Enregistrer la transaction
            $operationType = $this->operationTypeModel->getOperationTypeByCode('DEPOSIT');
            $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);

            file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " CREATE_TXN_START\n", FILE_APPEND);
            $this->transactionModel->createTransaction([
                'transaction_id' => $transactionId,
                'operation_type_id' => $operationType['id'],
                'client_id' => $clientId,
                'recipient_phone' => null,
                'amount' => $amount,
                'fee' => $fee,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Dépôt automatique',
                'status' => 'completed'
            ]);

            file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " CREATE_TXN_DONE\n", FILE_APPEND);
            session()->set('balance', $balanceAfter);

            file_put_contents(WRITEPATH . 'request_debug_deposit.txt', date('c') . " REDIRECTING_TO_DASHBOARD\n", FILE_APPEND);
            return redirect()->to('client/dashboard')->with('success', 'Dépôt effectué avec succès');
        }

        return view('client/deposit');
    }

    public function withdraw()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        if ($this->request->getMethod() === 'post' || $this->request->is('post')) {
            $amount = (float)$this->request->getPost('amount');
            if ($amount <= 0) {
                return redirect()->to('client/withdraw')->with('error', 'Montant invalide');
            }
            $clientId = session()->get('client_id');
            
            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];

            if ($amount > $balanceBefore) {
                return redirect()->to('client/withdraw')->with('error', 'Solde insuffisant');
            }

            // Calculer les frais de retrait
            $operationType = $this->operationTypeModel->getOperationTypeByCode('WITHDRAWAL');
            $feeSchedule = $this->feeScheduleModel->getFeeForAmount($operationType['id'], $amount);
            
            if (!$feeSchedule) {
                $fee = 0;
            } elseif ($feeSchedule['fee_percentage'] > 0) {
                $fee = ($amount * $feeSchedule['fee_percentage']) / 100;
            } else {
                $fee = $feeSchedule['fee_amount'];
            }

            $totalAmount = $amount + $fee;
            $balanceAfter = $balanceBefore - $totalAmount;

            // Mettre à jour le solde
            $this->clientModel->deductBalance($clientId, $totalAmount);

            // Enregistrer la transaction
            $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);

            $this->transactionModel->createTransaction([
                'transaction_id' => $transactionId,
                'operation_type_id' => $operationType['id'],
                'client_id' => $clientId,
                'recipient_phone' => null,
                'amount' => $amount,
                'fee' => $fee,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Retrait automatique',
                'status' => 'completed'
            ]);

            session()->set('balance', $balanceAfter);

            return redirect()->to('client/dashboard')->with('success', 'Retrait effectué avec succès');
        }

        return view('client/withdraw');
    }

    public function transfer()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        if ($this->request->getMethod() === 'post' || $this->request->is('post')) {
            $recipientPhone = $this->request->getPost('recipient_phone');
            $amount = (float)$this->request->getPost('amount');
            $includeWithdrawalFee = $this->request->getPost('include_withdrawal_fee') === '1';
            $clientId = session()->get('client_id');

            if ($amount <= 0) {
                return redirect()->to('client/transfer')->with('error', 'Montant invalide');
            }

            $recipientPhones = array_filter(array_map('trim', preg_split('/[\r\n,;]+/', $recipientPhone)));
            if (empty($recipientPhones)) {
                return redirect()->to('client/transfer')->with('error', 'Veuillez saisir au moins un destinataire');
            }

            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];
            $senderOperator = $this->validPrefixModel->getOperatorByPhone($client['phone_number']);
            $transferType = $this->operationTypeModel->getOperationTypeByCode('TRANSFER');
            $withdrawalType = $this->operationTypeModel->getOperationTypeByCode('WITHDRAWAL');
            $externalSurchargePercent = (float)($this->operatorConfigModel->getConfig('transfer_external_surcharge_percent') ?? 0);

            if (!$transferType) {
                return redirect()->to('client/transfer')->with('error', 'Impossible de traiter le transfert, type d\'opération introuvable');
            }

            $recipientOperators = [];
            foreach ($recipientPhones as $phone) {
                if (!$this->validPrefixModel->isValidPrefix($phone)) {
                    return redirect()->to('client/transfer')->with('error', 'Numéro invalide détecté : ' . $phone);
                }

                if ($phone === $client['phone_number']) {
                    return redirect()->to('client/transfer')->with('error', 'Impossible de transférer vers votre propre numéro : ' . $phone);
                }

                $recipientOperators[] = $this->validPrefixModel->getOperatorByPhone($phone);
            }

            if (count($recipientOperators) > 1 && count(array_unique($recipientOperators)) > 1) {
                return redirect()->to('client/transfer')->with('error', 'Pour un envoi multiple, tous les numéros doivent appartenir au même opérateur');
            }

            $recipientOperatorGroup = $recipientOperators[0] ?? null;
            $shareAmount = round($amount / count($recipientPhones), 2);
            $totalDeduction = 0;
            $transactions = [];
            $recipientBalances = [];
            $totalFees = 0;

            foreach ($recipientPhones as $index => $phone) {
                $share = $shareAmount;
                if ($index === count($recipientPhones) - 1) {
                    $share = round($amount - $shareAmount * ($index), 2);
                }

                $recipientOperator = $this->validPrefixModel->getOperatorByPhone($phone);
                $sameOperator = $senderOperator && $recipientOperator && $senderOperator === $recipientOperator;

                $feeSchedule = $this->feeScheduleModel->getFeeForAmount($transferType['id'], $share);
                $fee = 0;
                if ($feeSchedule) {
                    if ($feeSchedule['fee_percentage'] > 0) {
                        $fee = ($share * $feeSchedule['fee_percentage']) / 100;
                    } else {
                        $fee = $feeSchedule['fee_amount'];
                    }
                }

                $externalSurcharge = 0;
                if (!$sameOperator && $externalSurchargePercent > 0) {
                    $externalSurcharge = ($share * $externalSurchargePercent) / 100;
                }

                $withdrawalFee = 0;
                if ($sameOperator && $includeWithdrawalFee && $withdrawalType) {
                    $withdrawalSchedule = $this->feeScheduleModel->getFeeForAmount($withdrawalType['id'], $share);
                    if ($withdrawalSchedule) {
                        if ($withdrawalSchedule['fee_percentage'] > 0) {
                            $withdrawalFee = ($share * $withdrawalSchedule['fee_percentage']) / 100;
                        } else {
                            $withdrawalFee = $withdrawalSchedule['fee_amount'];
                        }
                    }
                }

                $totalFee = $fee + $externalSurcharge + $withdrawalFee;
                $deduction = $share + $totalFee;
                $totalDeduction += $deduction;
                $totalFees += $totalFee;

                $transactions[] = [
                    'phone' => $phone,
                    'amount' => $share,
                    'fee' => $totalFee,
                    'same_operator' => $sameOperator,
                    'recipient_operator' => $recipientOperator,
                    'description' => 'Transfert vers ' . $phone . ($recipientOperator ? ' (' . $recipientOperator . ')' : '')
                ];

                if ($sameOperator) {
                    $recipient = $this->clientModel->getClientByPhone($phone);
                    if (!$recipient) {
                        $recipientId = $this->clientModel->createClient([
                            'phone_number' => $phone,
                            'pin' => '0000',
                            'full_name' => 'Client ' . $phone,
                            'balance' => 0,
                            'status' => 'active'
                        ]);
                        $recipient = $this->clientModel->find($recipientId);
                    }
                    $recipientBalances[] = ['id' => $recipient['id'], 'amount' => $share];
                }
            }

            if ($totalDeduction > $balanceBefore) {
                return redirect()->to('client/transfer')->with('error', 'Solde insuffisant pour couvrir le montant et les frais');
            }

            $balanceAfter = $balanceBefore - $totalDeduction;
            $this->clientModel->deductBalance($clientId, $totalDeduction);

            foreach ($recipientBalances as $recipientBalance) {
                $this->clientModel->updateBalance($recipientBalance['id'], $recipientBalance['amount']);
            }

            foreach ($transactions as $transactionData) {
                $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);
                $this->transactionModel->createTransaction([
                    'transaction_id' => $transactionId,
                    'operation_type_id' => $transferType['id'],
                    'client_id' => $clientId,
                    'recipient_phone' => $transactionData['phone'],
                    'amount' => $transactionData['amount'],
                    'fee' => $transactionData['fee'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => $transactionData['description'],
                    'status' => 'completed'
                ]);
            }

            session()->set('balance', $balanceAfter);

            return redirect()->to('client/dashboard')->with('success', 'Transfert effectué avec succès');
        }

        return view('client/transfer');
    }

    public function history()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('client/login');
        }

        $clientId = session()->get('client_id');
        $transactions = $this->transactionModel->getTransactionsByClient($clientId);

        return view('client/history', ['transactions' => $transactions]);
    }
}
