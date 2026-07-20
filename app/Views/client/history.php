<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Historique des transactions</h4>
            </div>
            <div class="card-body">
                <?php if(empty($transactions)): ?>
                    <p>Aucune transaction</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Transaction</th>
                                    <th>Description</th>
                                    <th>Destinataire</th>
                                    <th>Montant</th>
                                    <th>Frais</th>
                                    <th>Solde avant</th>
                                    <th>Solde après</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction['transaction_id'] ?></td>
                                        <td><?= $transaction['description'] ?></td>
                                        <td><?= $transaction['recipient_phone'] ?? '-' ?></td>
                                        <td><?= number_format($transaction['amount'], 2) ?> Ar</td>
                                        <td><?= number_format($transaction['fee'], 2) ?> Ar</td>
                                        <td><?= number_format($transaction['balance_before'], 2) ?> Ar</td>
                                        <td><?= number_format($transaction['balance_after'], 2) ?> Ar</td>
                                        <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <a href="<?= base_url('client/dashboard') ?>" class="btn btn-secondary">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
