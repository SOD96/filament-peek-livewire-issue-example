@props(['model'])
<div id="article-content" class="prose prose-lg prose-primary mt-6 text-gray-500 max-w-none">
    {!! tiptap_converter()->asHTML($model->content) !!}
</div>
