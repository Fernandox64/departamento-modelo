@php($isEdit = isset($content))
<x-ui.form-field label="Titulo" name="title" :value="$content->title ?? ''" required />
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select">
                <option value="news" @selected(old('type', $content->type ?? '') === 'news')>Noticia</option>
                <option value="edital" @selected(old('type', $content->type ?? '') === 'edital')>Edital</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <x-ui.form-field label="Categoria" name="category" :value="$content->category ?? ''" />
    </div>
    <div class="col-md-4">
        <x-ui.form-field label="Publicacao" name="published_at" type="datetime-local" :value="isset($content->published_at) ? $content->published_at->format('Y-m-d\TH:i') : ''" />
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Resumo</label>
    <textarea class="form-control" name="summary" rows="2">{{ old('summary', $content->summary ?? '') }}</textarea>
    @error('summary')<div class="text-danger small">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Corpo</label>
    <textarea class="form-control" name="body" rows="8">{{ old('body', $content->body ?? '') }}</textarea>
    @error('body')<div class="text-danger small">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Imagem</label>
    <input type="file" name="image" class="form-control">
    @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $content->is_published ?? true))>
    <label class="form-check-label" for="is_published">Publicado</label>
</div>
<button class="btn btn-primary">{{ $isEdit ? 'Salvar Alteracoes' : 'Criar Conteudo' }}</button>
