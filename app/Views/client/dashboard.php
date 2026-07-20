<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h2>Bienvenue, <?= $client['full_name'] ?></h2>
                <h3 class="mt-3">Solde: <?= number_format($client['balance'], 2) ?> Ar</h3>
                <p class="mb-0">Numéro: <?= $client['phone_number'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Voir Solde</h5>
                <a href="<?= base_url('client/balance') ?>" class="btn btn-info">Consulter</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Dépôt</h5>
                <a href="<?= base_url('client/deposit') ?>" class="btn btn-success">Déposer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Retrait</h5>
                <a href="<?= base_url('client/withdraw') ?>" class="btn btn-warning">Retirer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Transfert</h5>
                <a href="<?= base_url('client/transfer') ?>" class="btn btn-primary">Transférer</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Transactions récentes</h5>
            </div>
            <div class="card-body">
                <?php if(empty($recent_transactions)): ?>
                    <p>Aucune transaction récente</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Transaction</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Frais</th>
                                    <th>Solde avant</th>
                                    <th>Solde après</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction['transaction_id'] ?></td>
                                        <td><?= $transaction['description'] ?></td>
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
                <a href="<?= base_url('client/history') ?>" class="btn btn-link">Voir tout l'historique</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
