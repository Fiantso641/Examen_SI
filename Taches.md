# Taches du Projet - Système Mobile Money

**Binôme :**
- Fiantso
- Toky

---

## Version 1 (v1) - Système Mobile Money de base  COMPLETED

### Fiantso 
- Création du fichier base.sql et les donnees de test 
- Création des tables : clients, valid_prefixes, operation_types, fee_schedules, transactions 
- Création du controller Client avec login automatique (numéro de téléphone) 
- Création du controller Operator pour l'administration 
- Mise en place des barèmes de frais par tranche (dépôt, retrait, transfert) 
- Configuration des préfixes valides (033, 037) 

### Toky 
- Création des modèles CodeIgniter (ClientModel, ValidPrefixModel, OperationTypeModel, FeeScheduleModel, TransactionModel, OperatorConfigModel) 
- Configuration des routes dans Routes.php 
- Mise en place de la structure des vues (layout principal avec Bootstrap) 
- Création de l'interface client (login, dashboard, solde, dépôt, retrait, transfert, historique) 
- Création de l'interface opérateur (dashboard, préfixes, opérations, frais, clients, transactions, rapports) 
- Mise à jour du controller Home pour rediriger vers login 
- Création des fichiers SCSS (_variables.scss, _mixins.scss, style.scss) 
- Compilation du SCSS en CSS (style.css) 
- Intégration du CSS personnalisé dans le layout 

---

## Fonctionnalités implémentées (v1)

## Toky
### Coté Client 
- Login automatique avec numéro de téléphone (pas d'inscription préalable) 
- Voir le solde 
- Faire un dépôt (automatique, gratuit) 
- Faire un retrait (automatique, avec frais par tranche) 
- Faire un transfert (avec frais par tranche) 
- Voir l'historique des transactions

## Fiantso
### Coté Opérateur 
- Configuration des préfixes valides (033, 037) 
- Création de types d'opérations (dépôt, retrait, transfert) 
- Barèmes de frais par tranche de montant (modifiable) 
- Situation des gains via les différents frais (retrait et transfert) 
- Situation des comptes clients 
- Rapports et statistiques 

### Styling 
- Structure SCSS avec variables et mixins 
- Template SCSS réutilisable 
- CSS compilé avec styles personnalisés 
- Design responsive et moderne 



