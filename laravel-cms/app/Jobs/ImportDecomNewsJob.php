<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\ImportRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportDecomNewsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $maxPages = 10)
    {
    }

    public function handle(): void
    {
        $run = ImportRun::create([
            'max_pages' => $this->maxPages,
            'status' => 'running',
            'started_at' => now(),
        ]);

        $processed = 0;
        $news = 0;
        $editais = 0;

        for ($page = 1; $page <= $this->maxPages; $page++) {
            $url = $page === 1
                ? 'https://www3.decom.ufop.br/decom/noticias/noticias/'
                : "https://www3.decom.ufop.br/decom/noticias/noticias/?page={$page}";

            $response = Http::withoutVerifying()->timeout(20)->get($url);
            if (!$response->ok()) {
                continue;
            }

            preg_match_all('/<li>(.*?)<\/li>/si', $response->body(), $matches);
            foreach (($matches[1] ?? []) as $raw) {
                $title = trim(strip_tags(html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                if ($title === '') {
                    continue;
                }
                $type = Str::contains(Str::lower($title), ['edital', 'matricula', 'monitoria']) ? 'edital' : 'news';
                $slug = Str::slug($title);
                if ($slug === '') {
                    continue;
                }

                Content::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'user_id' => 1,
                        'type' => $type,
                        'title' => $title,
                        'summary' => Str::limit($title, 200),
                        'category' => $type === 'edital' ? 'Editais' : 'Noticias',
                        'body' => 'Conteudo importado automaticamente.',
                        'published_at' => now(),
                        'is_published' => true,
                    ]
                );

                $processed++;
                $type === 'edital' ? $editais++ : $news++;
            }
        }

        $run->update([
            'processed' => $processed,
            'inserted_news' => $news,
            'inserted_editais' => $editais,
            'status' => 'done',
            'finished_at' => now(),
        ]);

        foreach (['home.news', 'home.editais', 'list.news', 'list.editais'] as $key) {
            Cache::forget($key);
        }
    }
}
