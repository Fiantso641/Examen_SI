<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        session_start();
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            return redirect()->to('/');
        }
        
        $user = $_SESSION['user'];
        
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Application de Régime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-heartbeat me-2"></i>Régime App
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i>' . htmlspecialchars($user['nom']) . '
                </span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-tachometer-alt me-2"></i>Menu
                        </h5>
                        <div class="list-group list-group-flush">
                            <a href="/dashboard" class="list-group-item list-group-item-action active">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-utensils me-2"></i>Régimes
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-wallet me-2"></i>Portefeuille
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-user me-2"></i>Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Welcome Message -->
                <div class="alert alert-success">
                    <h4><i class="fas fa-check-circle me-2"></i>Bienvenue ' . htmlspecialchars($user['nom']) . '!</h4>
                    <p class="mb-0">Votre application de régime alimentaire est fonctionnelle.</p>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-weight fa-3x text-primary mb-3"></i>
                                <h5>Gestion du Poids</h5>
                                <p class="text-muted">Suivez votre progression</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-utensils fa-3x text-success mb-3"></i>
                                <h5>Régimes</h5>
                                <p class="text-muted">Plans alimentaires</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                                <h5>Statistiques</h5>
                                <p class="text-muted">Vos progrès</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-bolt me-2"></i>Actions Rapides
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-plus me-2"></i>Nouveau Régime
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-weight me-2"></i>Mettre à jour le poids
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
        
        return $this->response->setBody($html);
    }
}
