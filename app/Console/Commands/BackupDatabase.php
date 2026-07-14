<?php

namespace App\Console\Commands;

use App\Mail\SystemAlertMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sauvegarde la base de données MySQL et compresse le fichier.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'backup-'.now()->format('Y-m-d_H-i-s').'.sql';
        $storagePath = storage_path('app/backups');

        if (! is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath.'/'.$filename;

        $this->info('Démarrage de la sauvegarde...');

        // On utilise mysqldump
        // Comme on est dans Docker, on se connecte à l'hôte 'db'
        // --skip-ssl est portable (MySQL + MariaDB), contrairement à --ssl-mode
        // Les identifiants sont passés via un fichier d'options temporaire
        // pour éviter toute fuite du mot de passe dans `ps` ou les logs/emails.
        $mysqlConfig = config('database.connections.mysql');

        $optionsFile = tempnam(sys_get_temp_dir(), 'mysqldump_');
        file_put_contents($optionsFile, implode("\n", [
            '[mysqldump]',
            'user='.$mysqlConfig['username'],
            'password='.$mysqlConfig['password'],
            'host='.$mysqlConfig['host'],
        ]));

        $command = sprintf(
            'mysqldump --defaults-extra-file=%s --skip-ssl %s > %s',
            escapeshellarg($optionsFile),
            escapeshellarg($mysqlConfig['database']),
            escapeshellarg($filePath)
        );

        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            @unlink($optionsFile);

            $this->info("Sauvegarde terminée : $filename");

            // Compression
            $this->info('Compression du fichier...');
            $zipPath = $filePath.'.gz';
            $zipProcess = Process::fromShellCommandline('gzip -9 '.escapeshellarg($filePath));
            $zipProcess->mustRun();

            $this->info('Fichier compressé : '.basename($zipPath));

            // Nettoyage des vieilles sauvegardes (plus de 7 jours)
            $this->cleanup();

        } catch (ProcessFailedException $exception) {
            @unlink($optionsFile);
            $errorMsg = 'Échec de la sauvegarde : '.$exception->getMessage();
            $this->error($errorMsg);

            // Envoi de l'alerte par email
            $adminEmails = array_filter(explode(',', env('ADMIN_NOTIFICATION_EMAILS'))) ?: ['digitaleflex@gmail.com', 'elfridayemadje5@gmail.com'];
            Mail::to($adminEmails)
                ->send(new SystemAlertMail($errorMsg, ['Task' => 'Database Backup']));
        }
    }

    protected function cleanup()
    {
        $this->info('Nettoyage des anciennes sauvegardes...');
        $files = glob(storage_path('app/backups/*.gz'));
        $now = time();
        $days = 7;

        foreach ($files as $file) {
            if ($now - filemtime($file) >= $days * 24 * 60 * 60) {
                unlink($file);
                $this->line('Supprimé : '.basename($file));
            }
        }
    }
}
