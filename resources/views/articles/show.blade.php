@props(['article'])

<x-dynamic-component
    :component="'generics.layouts.default'"
    :model="$article">
    @include('articles.content', [
        'article' => $article
    ])
</x-dynamic-component>
