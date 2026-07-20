<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Historique des Transactions</h2>

<div class="card">
    <div class="card-header">
        <h5>Toutes les transactions</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Transaction</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Destinataire</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction['transaction_id'] ?></td>
                        <td><?= $transaction['phone_number'] ?></td>
                        <td><?= $transaction['operation_name'] ?></td>
                        <td><?= $transaction['recipient_phone'] ?? '-' ?></td>
                        <td><?= number_format($transaction['amount'], 2) ?> Ar</td>
                        <td><?= number_format($transaction['fee'], 2) ?> Ar</td>
                        <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
