<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class PromoteUserToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promove um usuário a admin pelo e-mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Usuário não encontrado.');
            return 1;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("Usuário {$user->name} promovido a admin com sucesso!");
        return 0;
    }
}
