<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Rapports et Statistiques</h2>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total des Frais Collectés</h5>
                <h3><?= number_format($total_fees, 2) ?> Ar</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Solde Total des Clients</h5>
                <h3><?= number_format($total_balance, 2) ?> Ar</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Situation des gains par type d'opération</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Type d'opération</th>
                    <th>Code</th>
                    <th>Total des frais collectés</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($report_data as $report): ?>
                    <tr>
                        <td><?= $report['operation']['name'] ?></td>
                        <td><?= $report['operation']['code'] ?></td>
                        <td><strong><?= number_format($report['total_fees'], 2) ?> Ar</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
