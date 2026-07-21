# Taches du Projet - Système Mobile Money


## Version 1 (v1) - Système Mobile Money de base  COMPLETED

### Fiantso 
- Création du fichier base.sql et les donnees de test 
- Création des tables  clients, valid_prefixes, operation_types, fee_schedules, transactions 
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



# Tâches — Version 2 et amelioration de v1

## Liste des tâches

- **CRUD Préfixes opérateur** ( Fiantso)
	- Ajouter/Modifier/Supprimer des préfixes opérateur via l'interface.
	- Fichiers `app/Controllers/Operator.php`, `app/Models/ValidPrefixModel.php`, `app/Views/operator/prefixes.php`.
	- Critères création, édition et suppression persistent en base; messages flash présents.

- **Surcharge transferts externes** ( Toky)
	- Ajouter une configuration `transfer_external_surcharge_percent` et appliquer le calcul aux transferts externes.
	- Fichiers `app/Models/OperatorConfigModel.php`, `app/Controllers/Operator.php`, `app/Controllers/Client.php`.
	- Critères valeur configurable, appliquée aux frais, visible dans l'UI.

- **Rapports internes/externes** ( Fiantso)
	- Séparer le reporting des transferts internes vs externes et fournir un résumé par opérateur.
	- Fichiers `app/Controllers/Operator.php`, `app/Models/TransactionModel.php`.
	- Critères tableau récapitulatif des montants et frais.

- **Transferts multi-destinataires** ( Toky)
	- Permettre l'envoi simultané à plusieurs destinataires du même opérateur.
	- Fichiers `app/Controllers/Client.php`, `app/Models/TransactionModel.php`, `app/Views/client/transfer.php`.
	- Critères formulaire acceptant plusieurs numéros; transactions créées séparément.

- **Option frais de retrait** ( Fiantso)
	- Ajouter option `include_withdrawal_fee` pour inclure/exclure le frais de retrait lors d'un transfert.
	- Fichiers `app/Views/client/transfer.php`, `app/Controllers/Client.php`.
	- Critères montant total débité correct selon l'option.

- **Corriger boutons et POST (UI)** (Owner Toky)
	- Vérifier/corriger tous les formulaires où les boutons semblent inactifs (dépôt, retrait, transfert, config opérateur).
	- Fichiers vues/formulaires, `app/Config/Filters.php`, `app/Views/layout.php`.
	- Critères POST déclenche l'action, messages flash visibles.

        ## Alea1 Fiantso
		-promotion  en porcentzge sur les frais de transfer de name operateur
		-Creation de table promotion
