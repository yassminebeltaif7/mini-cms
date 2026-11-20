<x-layout>
    <x-slot:title>{{ $post->title }} - Mini-CMS</x-slot:title>

    <article class="max-w-2xl">
        <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">Retour aux articles</a>

        <h1 class="text-3xl font-bold mt-4 mb-2">{{ $post->title }}</h1>

        <p class="text-sm text-gray-500 mb-6">
            @if ($post->published_at)
                Publié le {{ $post->published_at->format('d/m/Y') }}
            @else
                Brouillon
            @endif
        </p>

        @if ($post->excerpt)
            <p class="text-lg text-gray-600 mb-6">{{ $post->excerpt }}</p>
        @endif

        <div class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $post->body }}</div>

        <div class="mt-8 flex flex-wrap items-start gap-4">
            <a href="{{ route('posts.edit', $post) }}" class="rounded-md border border-indigo-600 px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">Modifier</a>

            <details class="text-sm">
                <summary class="cursor-pointer text-red-600 hover:underline">Supprimer cet article</summary>
                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="mt-3 rounded-md border border-red-200 bg-red-50 p-4 max-w-md">
                    @csrf
                    @method('DELETE')
                    <p class="mb-3 text-gray-700">Cette action est définitive. Confirmez-vous la suppression de « {{ $post->title }} » ?</p>
                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">Confirmer la suppression</button>
                </form>
            </details>
        </div>
    </article>
</x-layout>
