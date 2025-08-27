@props(['article'])
<main class="mx-auto px-4 lg:px-8">
    <div class="lg:pt-5 relative">
        <section aria-labelledby="article-heading" class="lg:col-span-2 xl:col-span-3">
            <div
                class="lg:pt-5 lg:grid lg:gap-x-8 xl:grid-cols-3">
                <section aria-labelledby="article-heading" class="lg:col-span-2 xl:col-span-3">
                    @include('partials.content.article-content', ['model' => $article])
                </section>
            </div>
        </section>
    </div>
</main>
