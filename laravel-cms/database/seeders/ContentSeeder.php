<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@example.com')->first();
        if (!$user) {
            return;
        }

        Content::updateOrCreate(
            ['slug' => 'portal-institucional-em-laravel'],
            [
                'user_id' => $user->id,
                'type' => 'news',
                'title' => 'Portal institucional em Laravel',
                'summary' => 'Novo portal com componentes, politicas e painel administrativo.',
                'category' => 'Departamento',
                'body' => '<p>Portal modernizado com recursos inspirados no ecossistema Laravel.</p>',
                'published_at' => now(),
                'is_published' => true,
            ]
        );

        Content::updateOrCreate(
            ['slug' => 'edital-monitoria-laravel'],
            [
                'user_id' => $user->id,
                'type' => 'edital',
                'title' => 'Edital de monitoria',
                'summary' => 'Processo seletivo para monitorias do semestre.',
                'category' => 'Editais',
                'body' => '<p>Inscricoes abertas para monitorias.</p>',
                'published_at' => now(),
                'is_published' => true,
            ]
        );
    }
}
