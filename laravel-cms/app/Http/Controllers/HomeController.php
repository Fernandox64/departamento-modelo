<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $news = Cache::remember('home.news', now()->addMinutes(10), fn () => Content::published()->type('news')->latest('published_at')->take(6)->get());
        $editais = Cache::remember('home.editais', now()->addMinutes(10), fn () => Content::published()->type('edital')->latest('published_at')->take(6)->get());

        return view('home', compact('news', 'editais'));
    }

    public function news()
    {
        $items = Cache::remember('list.news', now()->addMinutes(10), fn () => Content::published()->type('news')->latest('published_at')->paginate(12));
        return view('content.index', ['items' => $items, 'type' => 'news', 'title' => 'Noticias']);
    }

    public function editais()
    {
        $items = Cache::remember('list.editais', now()->addMinutes(10), fn () => Content::published()->type('edital')->latest('published_at')->paginate(12));
        return view('content.index', ['items' => $items, 'type' => 'edital', 'title' => 'Editais']);
    }

    public function show(string $slug)
    {
        $item = Content::published()->where('slug', $slug)->firstOrFail();
        return view('content.show', compact('item'));
    }
}
