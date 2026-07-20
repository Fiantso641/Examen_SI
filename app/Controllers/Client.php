<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ValidPrefixModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;
use App\Models\FeeScheduleModel;

class Client extends BaseController
{
    protected $clientModel;
    protected $validPrefixModel;
    protected $transactionModel;
    protected $operationTypeModel;
    protected $feeScheduleModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->validPrefixModel = new ValidPrefixModel();
        $this->transactionModel = new TransactionModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeScheduleModel = new FeeScheduleModel();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post' || $this->request->is('post')) {
            $phone = $this->request->getPost('phone');
            $pin = $this->request->getPost('pin');

            // Vérifier si le préfixe est valide
            if (!$this->validPrefixModel->isValidPrefix($phone)) {
                return redirect()->to('/index.php/client/login')->with('error', 'Numéro de téléphone non valide (préfixe non reconnu)');
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
                    return redirect()->to('/index.php/client/login')->with('error', 'PIN incorrect');
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
        return redirect()->to('/index.php/client/login');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/index.php/client/login');
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
            return redirect()->to('/index.php/client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);
        
        session()->set('balance', $client['balance']);

        return view('client/balance', ['client' => $client]);
    }

    public function deposit()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/index.php/client/login');
        }

        if ($this->request->getMethod() === 'post') {
            $amount = $this->request->getPost('amount');
            $clientId = session()->get('client_id');
            
            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];

            // Calculer les frais (dépôt = gratuit)
            $fee = 0;
            $balanceAfter = $balanceBefore + $amount;

            // Mettre à jour le solde
            $this->clientModel->updateBalance($clientId, $amount);

            // Enregistrer la transaction
            $operationType = $this->operationTypeModel->getOperationTypeByCode('DEPOSIT');
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
                'description' => 'Dépôt automatique',
                'status' => 'completed'
            ]);

            session()->set('balance', $balanceAfter);

            return redirect()->to('/index.php/client/dashboard')->with('success', 'Dépôt effectué avec succès');
        }

        return view('client/deposit');
    }

    public function withdraw()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/index.php/client/login');
        }

        if ($this->request->getMethod() === 'post') {
            $amount = $this->request->getPost('amount');
            $clientId = session()->get('client_id');
            
            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];

            if ($amount > $balanceBefore) {
                return redirect()->to('/index.php/client/withdraw')->with('error', 'Solde insuffisant');
            }

            // Calculer les frais de retrait
            $operationType = $this->operationTypeModel->getOperationTypeByCode('WITHDRAWAL');
            $feeSchedule = $this->feeScheduleModel->getFeeForAmount($operationType['id'], $amount);
            
            if ($feeSchedule['fee_percentage'] > 0) {
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

            return redirect()->to('/index.php/client/dashboard')->with('success', 'Retrait effectué avec succès');
        }

        return view('client/withdraw');
    }

    public function transfer()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/index.php/client/login');
        }

        if ($this->request->getMethod() === 'post') {
            $recipientPhone = $this->request->getPost('recipient_phone');
            $amount = $this->request->getPost('amount');
            $clientId = session()->get('client_id');
            
            $client = $this->clientModel->find($clientId);
            $balanceBefore = $client['balance'];

            // Vérifier si le destinataire existe
            $recipient = $this->clientModel->getClientByPhone($recipientPhone);
            if (!$recipient) {
                return redirect()->to('/index.php/client/transfer')->with('error', 'Destinataire non trouvé');
            }

            if ($recipient['id'] === $clientId) {
                return redirect()->to('/index.php/client/transfer')->with('error', 'Impossible de transférer vers votre propre compte');
            }

            if ($amount > $balanceBefore) {
                return redirect()->to('/index.php/client/transfer')->with('error', 'Solde insuffisant');
            }

            // Calculer les frais de transfert
            $operationType = $this->operationTypeModel->getOperationTypeByCode('TRANSFER');
            $feeSchedule = $this->feeScheduleModel->getFeeForAmount($operationType['id'], $amount);
            
            if ($feeSchedule['fee_percentage'] > 0) {
                $fee = ($amount * $feeSchedule['fee_percentage']) / 100;
            } else {
                $fee = $feeSchedule['fee_amount'];
            }

            $totalAmount = $amount + $fee;
            $balanceAfter = $balanceBefore - $totalAmount;

            // Déduire du solde de l'expéditeur
            $this->clientModel->deductBalance($clientId, $totalAmount);

            // Ajouter au solde du destinataire
            $this->clientModel->updateBalance($recipient['id'], $amount);

            // Enregistrer la transaction
            $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);

            $this->transactionModel->createTransaction([
                'transaction_id' => $transactionId,
                'operation_type_id' => $operationType['id'],
                'client_id' => $clientId,
                'recipient_phone' => $recipientPhone,
                'amount' => $amount,
                'fee' => $fee,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Transfert vers ' . $recipientPhone,
                'status' => 'completed'
            ]);

            session()->set('balance', $balanceAfter);

            return redirect()->to('/index.php/client/dashboard')->with('success', 'Transfert effectué avec succès');
        }

        return view('client/transfer');
    }

    public function history()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/index.php/client/login');
        }

        $clientId = session()->get('client_id');
        $transactions = $this->transactionModel->getTransactionsByClient($clientId);

        return view('client/history', ['transactions' => $transactions]);
    }
}
