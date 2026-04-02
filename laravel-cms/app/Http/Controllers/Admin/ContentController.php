<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use App\Models\User;
use App\Notifications\NewEditalPublished;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Content::class, 'content');
    }

    public function index()
    {
        $items = Content::query()->latest('published_at')->paginate(20);
        return view('admin.contents.index', compact('items'));
    }

    public function create()
    {
        return view('admin.contents.create');
    }

    public function store(StoreContentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(5));
        $data['is_published'] = (bool)($data['is_published'] ?? true);
        $data['published_at'] = $data['published_at'] ?? now();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('contents', 'public');
        }

        $content = Content::create($data);

        if ($content->type === 'edital') {
            User::query()->whereNotNull('email_verified_at')->each(function (User $user) use ($content): void {
                $user->notify(new NewEditalPublished($content));
            });
        }

        $this->flushCache();
        return redirect()->route('admin.contents.index')->with('success', 'Conteudo criado com sucesso.');
    }

    public function show(Content $content)
    {
        return redirect()->route('admin.contents.edit', $content);
    }

    public function edit(Content $content)
    {
        return view('admin.contents.edit', compact('content'));
    }

    public function update(UpdateContentRequest $request, Content $content)
    {
        $data = $request->validated();
        $data['is_published'] = (bool)($data['is_published'] ?? false);

        if ($request->hasFile('image')) {
            if ($content->image_path) {
                Storage::disk('public')->delete($content->image_path);
            }
            $data['image_path'] = $request->file('image')->store('contents', 'public');
        }

        $content->update($data);
        $this->flushCache();
        return redirect()->route('admin.contents.index')->with('success', 'Conteudo atualizado com sucesso.');
    }

    public function destroy(Content $content)
    {
        if ($content->image_path) {
            Storage::disk('public')->delete($content->image_path);
        }
        $content->delete();
        $this->flushCache();
        return redirect()->route('admin.contents.index')->with('success', 'Conteudo removido com sucesso.');
    }

    private function flushCache(): void
    {
        foreach (['home.news', 'home.editais', 'list.news', 'list.editais'] as $key) {
            Cache::forget($key);
        }
    }
}
