<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Configuration des transferts externes</h2>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Pourcentage additionnel pour les transferts vers d'autres opérateurs</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/config') ?>">
                    <div class="mb-3">
                        <label for="transfer_external_surcharge_percent" class="form-label">Pourcentage (%)</label>
                           <input type="number"
                               class="form-control"
                               id="transfer_external_surcharge_percent"
                               name="transfer_external_surcharge_percent"
                               min="0"
                               step="0.01"
                               value="<?= esc($external_surcharge_percent) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="<?= base_url('operator/dashboard') ?>" class="btn btn-secondary">Retour</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
