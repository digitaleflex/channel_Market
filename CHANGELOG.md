# Changelog

## [Unreleased]

### Corrigé
- **Sauvegarde B2 cassée** : `backup:run` échouait chaque jour à 02:00 (`mysqldump: unknown variable 'ssl-mode=DISABLED'`) et envoyait une alerte email quotidienne. Le client `mysqldump` des conteneurs est MariaDB 10.19, qui ne supporte pas `--ssl-mode` (MySQL 8.4+). `config/database.php` utilise désormais `skip_ssl` + `ssl_flag => 'skip-ssl'`.
- **Config cache obsolète** : `bootstrap/cache/config.php` conservait `--ssl-mode=DISABLED` alors que la source avait été corrigée — la correction était sans effet. Purge + régénération.
- **51 sauvegardes vides** (mai→juillet) et 2 fichiers `.sql` de 0 octet supprimés. `db:backup` ne supprime plus un dump vide et alerte au lieu de le compter comme un succès.
- **`laravel.log` à 93 Mo** : tronqué à 200 Ko, rotation ajoutée (`/etc/logrotate.d/channel-market-logs`, daily, 7 rotations, maxsize 50M).
- **Liste des backups vide sur le dashboard** : `ActivityController` scannait `storage/app/backup` (singulier) et `*.zip`, chemin et extension inexistants. Pointe désormais sur `storage/app/private/channel-market-backup`, 10 plus récents.
- **Test de restauration mensuel** : `backup_restore_test.yml` pointait sur `backups/db_backup_*.sql.gz`. Extrait désormais le dump SQL depuis l'archive Spatie.

### Modifié
- **Sauvegarde unifiée** : un seul système — Spatie `backup:run` (local + B2, le seul off-site). `db:backup` et le workflow GitHub `backups.yml` supprimés : tous deux n'écrivaient que sur le disque du VPS, sans copie externe, et faisaient 3× le même dump.
- **Planification** (`bootstrap/app.php`) : `backup:run` 02:00, `backup:clean` 03:00 (après le run, plus avant), `system:monitor` horaire.
- **`config/backup.php`** : `files.include` ne contient plus `base_path()` (tout le projet) mais une liste explicite. Archive passée de 2091 fichiers / 32.77 Mo à 200 fichiers / 26.32 Mo — `.git/`, `laravel.log` et les sauvegardes des autres systèmes n'y sont plus embarqués. Les ~26 Mo restants sont les PDF et images produits (donnée métier réelle).

## [1.0.0] - 2026-06-17

### Ajouté
- **SoftDeletes** sur le modèle `Product` — les produits ne sont plus supprimés définitivement (migration `add_deleted_at_to_products_table`)
- **Backblaze B2** comme destination de sauvegarde cloud via l'API S3-compatible
- Commande `backup:copy-to-google` (retirée ensuite, remplacée par B2)
- Planification des sauvegardes dans le scheduler (`bootstrap/app.php`) :
  - `db:backup` à 00:00
  - `backup:clean` à 01:00
  - `backup:run` à 02:00 (local + B2)
  - `system:monitor` toutes les heures

### Modifié
- **`config/backup.php`** : ajout du disque `s3` (Backblaze B2) comme destination, `continue_on_failure: true`
- **`config/database.php`** : correction SSL (`--ssl=0` au lieu de `--ssl-mode=DISABLED`) pour compatibilité MariaDB
- **`config/filesystems.php`** : retrait du disque `google` (Google Drive)
- **`app/Providers/AppServiceProvider.php`** : retrait de l'extension Storage Google Drive
- **`composer.json`** : ajout de `league/flysystem-aws-s3-v3` pour le support S3

### Supprimé
- **`app/Console/Commands/CopyBackupToGoogleDrive.php`** : commande obsolète (remplacée par Spatie backup direct vers B2)
- **`storage/app/google-drive-credentials.json`** : credentials Google Drive
- Variables d'environnement `GOOGLE_DRIVE_FOLDER_ID`, `GOOGLE_DRIVE_TEAM_DRIVE_ID` et `S3_*` inutilisées

### Sécurité
- Les sauvegardes sont chiffrées et stockées sur Backblaze B2 (rétention configurée dans Spatie)
- Plus de dépendance à un service account Google (qui nécessitait un Shared Drive)

### Technique
- Installation de `league/flysystem-aws-s3-v3` (dépendance S3)
- Cache de production optimisé (`php artisan optimize`)
- Volumes Docker persistants pour `storage/` et `logs/`
