# Changelog

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
