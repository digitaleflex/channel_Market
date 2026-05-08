<?php

namespace App\Console\Commands;

use App\Mail\SystemAlertMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MonitorSystem extends Command
{
    protected $signature = 'system:monitor';

    protected $description = 'Vérifie la santé du système et envoie une alerte si nécessaire.';

    public function handle()
    {
        $errors = [];
        $context = [
            'Time' => now()->toDateTimeString(),
            'Environment' => config('app.env'),
        ];

        // 1. Vérification Base de Données
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $errors[] = 'Base de données inaccessible : '.$e->getMessage();
        }

        // 2. Vérification Espace Disque (Alerte si < 10%)
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());
        $percentFree = ($freeSpace / $totalSpace) * 100;

        if ($percentFree < 10) {
            $errors[] = 'Espace disque critique : '.round($percentFree, 2).'% restant.';
        }

        // 3. Envoi de l'alerte si des erreurs sont détectées
        if (! empty($errors)) {
            $alertRecipient = 'digitaleflex@gmail.com';
            $message = implode("\n", $errors);

            Mail::to(config('mail.from.address')) // Destinataire principal (config app)
                ->bcc($alertRecipient)            // BCC demandé par le client
                ->send(new SystemAlertMail($message, $context));

            $this->error("Problèmes détectés ! Alerte envoyée à $alertRecipient");
        } else {
            $this->info('Système en bonne santé.');
        }
    }
}
