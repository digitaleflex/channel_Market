<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Deployment;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities and deployments (Admin).
     */
    public function index()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(10, ['*'], 'activities_page');

        $deployments = Deployment::latest()
            ->paginate(10, ['*'], 'deployments_page');

        return view('admin.activity.index', compact('activities', 'deployments'));
    }

    /**
     * Display the interactive workflows dashboard.
     */
    public function workflows()
    {
        // 1. Get latest deployment
        $latestDeployment = Deployment::latest()->first();

        // 2. Fetch latest GitHub Actions run status (failsafe)
        $githubStatus = null;
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Channel-Market-App',
            ])->timeout(3)->get('https://api.github.com/repos/digitaleflex/channel_Market/actions/runs');

            if ($response->successful()) {
                $runs = $response->json()['workflow_runs'] ?? [];
                // Find latest CI/CD & Deployment run
                foreach ($runs as $run) {
                    if (str_contains($run['name'] ?? '', 'CI/CD') || str_contains($run['path'] ?? '', 'deploy.yml')) {
                        $githubStatus = [
                            'id' => $run['id'],
                            'status' => $run['status'],
                            'conclusion' => $run['conclusion'],
                            'html_url' => $run['html_url'],
                            'message' => $run['head_commit']['message'] ?? '',
                            'author' => $run['head_commit']['author']['name'] ?? '',
                            'created_at' => $run['created_at'],
                        ];
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore API exceptions
        }

        // 3. Scan local backups
        $backupFiles = [];
        try {
            $backupDir = storage_path('app/backup');
            if (file_exists($backupDir)) {
                $files = glob($backupDir.'/*.zip');
                foreach ($files as $file) {
                    $backupFiles[] = [
                        'name' => basename($file),
                        'size' => round(filesize($file) / 1024 / 1024, 2).' MB',
                        'date' => date('d/m/Y H:i:s', filemtime($file)),
                    ];
                }
            }
        } catch (\Throwable $e) {
        }

        // 4. Retrieve real-time system metrics
        $systemMetrics = [
            'disk_free' => 'N/A',
            'disk_total' => 'N/A',
            'disk_percent' => 0,
            'memory_used' => 'N/A',
            'memory_total' => 'N/A',
            'memory_percent' => 0,
            'cpu_load' => 'N/A',
            'docker_app_status' => 'online',
            'docker_db_status' => 'online',
        ];

        try {
            // Disk space
            $free = disk_free_space('/');
            $total = disk_total_space('/');
            if ($free !== false && $total !== false) {
                $used = $total - $free;
                $systemMetrics['disk_free'] = round($free / 1024 / 1024 / 1024, 1).' GB';
                $systemMetrics['disk_total'] = round($total / 1024 / 1024 / 1024, 1).' GB';
                $systemMetrics['disk_percent'] = round(($used / $total) * 100);
            }

            // Memory usage (Linux specific parses /proc/meminfo)
            if (file_exists('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                preg_match('/MemTotal:\s+(\d+)/', $meminfo, $totalMatches);
                preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $availMatches);
                if (! empty($totalMatches[1]) && ! empty($availMatches[1])) {
                    $memTotal = intval($totalMatches[1]) * 1024; // Convert to bytes
                    $memAvail = intval($availMatches[1]) * 1024;
                    $memUsed = $memTotal - $memAvail;
                    $systemMetrics['memory_total'] = round($memTotal / 1024 / 1024 / 1024, 1).' GB';
                    $systemMetrics['memory_used'] = round($memUsed / 1024 / 1024 / 1024, 1).' GB';
                    $systemMetrics['memory_percent'] = round(($memUsed / $memTotal) * 100);
                }
            }

            // CPU load
            if (function_exists('sys_getloadavg')) {
                $load = sys_getloadavg();
                if ($load) {
                    $systemMetrics['cpu_load'] = $load[0].' (1 min)';
                }
            }

            // Check container status using DB connection
            DB::connection()->getPdo();
            $systemMetrics['docker_db_status'] = 'online';
        } catch (\Throwable $e) {
            $systemMetrics['docker_db_status'] = 'offline';
        }

        return view('admin.activity.workflows', compact('latestDeployment', 'githubStatus', 'backupFiles', 'systemMetrics'));
    }

    /**
     * AJAX Endpoint: Trigger database backup.
     */
    public function runBackup()
    {
        try {
            Artisan::queue('backup:run');

            return response()->json([
                'success' => true,
                'message' => 'Tâche de sauvegarde lancée en arrière-plan avec succès !',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec du lancement : '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX Endpoint: Trigger system monitor check.
     */
    public function runMonitor()
    {
        try {
            Artisan::call('monitor:system');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Diagnostic système effectué !',
                'output' => trim($output),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec du diagnostic : '.$e->getMessage(),
            ], 500);
        }
    }
}
