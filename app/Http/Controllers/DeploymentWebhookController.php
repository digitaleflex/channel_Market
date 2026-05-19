<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DeploymentWebhookController extends Controller
{
    /**
     * Handle incoming deployment status notification.
     */
    public function handle(Request $request)
    {
        $token = $request->header('X-Deploy-Token') ?? $request->query('token');
        $expectedToken = env('DEPLOY_WEBHOOK_TOKEN', 'default-secure-deploy-token-123');

        if ($token !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'commit_sha' => 'required|string',
            'commit_message' => 'nullable|string',
            'status' => 'required|string|in:deploying,success,failed',
            'log_output' => 'nullable|string',
        ]);

        $sha = $validated['commit_sha'];
        $status = $validated['status'];

        $deployment = Deployment::where('commit_sha', $sha)->first();

        if (! $deployment) {
            $deployment = new Deployment([
                'commit_sha' => $sha,
                'commit_message' => $validated['commit_message'] ?? 'Déploiement initié',
                'status' => $status,
                'started_at' => Carbon::now(),
            ]);
        } else {
            $deployment->status = $status;
            if ($status === 'success' || $status === 'failed') {
                $deployment->finished_at = Carbon::now();
                $deployment->duration = $deployment->started_at ? Carbon::now()->diffInSeconds($deployment->started_at) : null;
            }
        }

        if (isset($validated['log_output'])) {
            $deployment->log_output = $validated['log_output'];
        }

        $deployment->save();

        return response()->json(['ok' => true, 'deployment_id' => $deployment->id]);
    }
}
