<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Tableau de bord Opérateur</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h3><?= $total_clients ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Solde Total</h5>
                <h3><?= number_format($total_balance, 2) ?> Ar</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Transactions</h5>
                <h3><?= $total_transactions ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Total Frais</h5>
                <h3><?= number_format($total_fees, 2) ?> Ar</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Préfixes Valides</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach($valid_prefixes as $prefix): ?>
                        <li class="list-group-item">
                            <strong><?= $prefix['prefix'] ?></strong> - <?= $prefix['operator_name'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= base_url('operator/prefixes') ?>" class="btn btn-primary mt-3">Gérer les préfixes</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('operator/clients') ?>" class="btn btn-outline-primary">Voir les clients</a>
                    <a href="<?= base_url('operator/transactions') ?>" class="btn btn-outline-primary">Voir les transactions</a>
                    <a href="<?= base_url('operator/fees') ?>" class="btn btn-outline-primary">Gérer les barèmes de frais</a>
                    <a href="<?= base_url('operator/promotions') ?>" class="btn btn-outline-primary">Gérer les promotions</a>
                    <a href="<?= base_url('operator/config') ?>" class="btn btn-outline-primary">Configuration externe</a>
                    <a href="<?= base_url('operator/reports') ?>" class="btn btn-outline-primary">Voir les rapports</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
