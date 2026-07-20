<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Votre Solde</h4>
            </div>
            <div class="card-body text-center">
                <h2 class="display-4 text-primary"><?= number_format($client['balance'], 2) ?> Ar</h2>
                <p class="text-muted">Numéro: <?= $client['phone_number'] ?></p>
                <a href="<?= base_url('index.php/client/dashboard') ?>" class="btn btn-secondary">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
