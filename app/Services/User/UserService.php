<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BemVindoMail;

class UserService
{
    /**
     * Cria um novo usuário com senha gerada automaticamente
     */
    public function createUser(array $data): User
    {
        // Gerar senha aleatória
        $senha = Str::random(8);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($senha),
            'telefone' => $data['telefone'] ?? null,
            'cargo' => $data['cargo'] ?? null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'cpf' => $data['cpf'] ?? null,
            'is_admin' => $data['is_admin'] ?? false,
            'precisa_trocar_senha' => true,
        ]);

        // Enviar email de boas-vindas com a senha
        try {
            Mail::to($user->email)->send(new BemVindoMail($user, $senha));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
        }

        return $user;
    }

    /**
     * Atualiza dados do usuário
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? $user->telefone,
            'cargo' => $data['cargo'] ?? $user->cargo,
            'data_nascimento' => $data['data_nascimento'] ?? $user->data_nascimento,
            'cpf' => $data['cpf'] ?? $user->cpf,
        ]);

        return $user->fresh();
    }

    /**
     * Reseta a senha do usuário
     */
    public function resetPassword(User $user): string
    {
        $novaSenha = Str::random(8);
        
        $user->update([
            'password' => Hash::make($novaSenha),
            'precisa_trocar_senha' => true,
        ]);

        // Enviar email com nova senha
        try {
            Mail::to($user->email)->send(new BemVindoMail($user, $novaSenha));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de reset de senha: ' . $e->getMessage());
        }

        return $novaSenha;
    }

    /**
     * Alterna status de administrador
     */
    public function toggleAdmin(User $user): User
    {
        $user->update([
            'is_admin' => !$user->is_admin
        ]);

        return $user->fresh();
    }

    /**
     * Valida dados únicos do usuário
     */
    public function validateUniqueData(array $data, ?User $user = null): array
    {
        $errors = [];

        // Validar email único
        $emailQuery = User::where('email', $data['email']);
        if ($user) {
            $emailQuery->where('id', '!=', $user->id);
        }
        
        if ($emailQuery->exists()) {
            $errors['email'][] = 'Este email já está sendo usado por outro usuário.';
        }

        // Validar CPF único (se fornecido)
        if (!empty($data['cpf'])) {
            $cpfQuery = User::where('cpf', $data['cpf']);
            if ($user) {
                $cpfQuery->where('id', '!=', $user->id);
            }
            
            if ($cpfQuery->exists()) {
                $errors['cpf'][] = 'Este CPF já está sendo usado por outro usuário.';
            }
        }

        return $errors;
    }

    /**
     * Busca usuários com filtros
     */
    public function searchUsers(array $filters = [])
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_admin'])) {
            $query->where('is_admin', $filters['is_admin']);
        }

        return $query->orderBy('name')->paginate(15);
    }

    /**
     * Obtém estatísticas dos usuários
     */
    public function getUserStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'users_need_password_change' => User::where('precisa_trocar_senha', true)->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }
}
