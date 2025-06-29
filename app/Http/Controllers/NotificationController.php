<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Viagem;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Obter chave VAPID pública
     */
    public function getVapidKey()
    {
        // Em produção, use variáveis de ambiente
        $publicKey = env('VAPID_PUBLIC_KEY', 'BEl62iUYgUivxIkv69yViEuiBIa40HI80NMEy_qzlJNgzq2BPZFhC_xDUGGsIhm7YLRQcKGfLUBSsD_gZlDtNNw');
        
        return response()->json([
            'publicKey' => $publicKey
        ]);
    }

    /**
     * Registrar subscription do usuário
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|string',
            'subscription.keys' => 'required|array',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
        ]);

        $user = auth()->user();
        
        try {
            // Deletar subscription anterior se existir
            PushSubscription::where('user_id', $user->id)->delete();
            
            // Criar nova subscription
            PushSubscription::create([
                'user_id' => $user->id,
                'endpoint' => $request->subscription['endpoint'],
                'p256dh_key' => $request->subscription['keys']['p256dh'],
                'auth_token' => $request->subscription['keys']['auth'],
                'user_agent' => $request->user_agent ?? $request->header('User-Agent'),
            ]);

            return response()->json(['message' => 'Subscription registrada com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar push subscription: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno'], 500);
        }
    }

    /**
     * Cancelar subscription
     */
    public function unsubscribe(Request $request)
    {
        $user = auth()->user();
        
        PushSubscription::where('user_id', $user->id)->delete();
        
        return response()->json(['message' => 'Subscription removida com sucesso']);
    }

    /**
     * Simular envio de notificação (sem biblioteca externa)
     */
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();
        
        if ($subscriptions->isEmpty()) {
            return false;
        }

        // Log da notificação (em produção, aqui seria o envio real)
        Log::info("Notificação simulada para usuário {$userId}: {$title} - {$body}");
        
        // Simular envio bem-sucedido
        return true;
    }

    /**
     * Notificar mudança de status de viagem
     */
    public function notifyTripStatusChange(Viagem $viagem, $oldStatus)
    {
        $statusMessages = [
            'aprovado' => 'Sua viagem foi aprovada!',
            'rejeitado' => 'Sua viagem foi rejeitada.',
            'pendente' => 'Sua viagem está em análise.',
        ];

        $title = 'Status da Viagem Atualizado';
        $body = $statusMessages[$viagem->status] ?? 'Status da viagem alterado.';
        $body .= " Viagem para {$viagem->destino}.";

        $data = [
            'url' => route('viagens.show', $viagem->id),
            'viagem_id' => $viagem->id,
            'status' => $viagem->status,
        ];

        return $this->sendToUser($viagem->user_id, $title, $body, $data);
    }

    /**
     * Notificar administradores
     */
    public function notifyAdmins($title, $body, $data = [])
    {
        $adminUsers = User::where('is_admin', true)->get();
        
        foreach ($adminUsers as $admin) {
            $this->sendToUser($admin->id, $title, $body, $data);
        }
    }

    /**
     * Testar notificação
     */
    public function testNotification(Request $request)
    {
        $user = auth()->user();
        
        $result = $this->sendToUser(
            $user->id,
            'Notificação de Teste',
            'Esta é uma notificação de teste do sistema Diário de Bordo.',
            ['test' => true]
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Notificação enviada (simulada)!' : 'Nenhuma subscription ativa encontrada.'
        ]);
    }
}
