<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\BemVindoMail;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable',
            'cargo' => 'nullable',
            'data_nascimento' => 'nullable|date',
            'cpf' => 'nullable|string|size:14|unique:users,cpf',
            'is_admin' => 'nullable|boolean',
        ]);
        $senha = substr(bin2hex(random_bytes(4)),0,8);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($senha),
            'telefone' => $request->telefone,
            'cargo' => $request->cargo,
            'data_nascimento' => $request->data_nascimento,
            'cpf' => $request->cpf,
            'is_admin' => $request->is_admin ? true : false,
            'precisa_trocar_senha' => true,
        ]);

        // Tentar enviar email com tratamento de erro
        try {
            Mail::to($user->email)->send(new BemVindoMail($user, $senha));
            $message = 'Usuário criado com sucesso! A senha foi enviada por e-mail.';
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
            $message = 'Usuário criado com sucesso! Senha temporária: ' . $senha . ' (Email não pôde ser enviado)';
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'nullable',
            'cargo' => 'nullable',
            'data_nascimento' => 'nullable',
            'cpf' => 'nullable|string|size:14|unique:users,cpf,' . $user->id,
        ]);
        $user->update($request->only(['name','email','telefone','cargo','data_nascimento','cpf']));
        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $novaSenha = substr(bin2hex(random_bytes(4)),0,8);
        $user->password = Hash::make($novaSenha);
        $user->save();
        
        // Tentar enviar email com tratamento de erro
        try {
            Mail::to($user->email)->send(new BemVindoMail($user));
            $message = 'Senha redefinida e enviada por e-mail para: ' . $user->email;
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de redefinição de senha: ' . $e->getMessage());
            $message = 'Senha redefinida para: ' . $novaSenha . ' (Email não pôde ser enviado)';
        }
        
        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído!');
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();
        return response()->json(['success' => true, 'is_admin' => $user->is_admin]);
    }
}
