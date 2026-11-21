<x-layout>
    <x-slot:title>Catégorie introuvable - Mini-CMS</x-slot:title>

    <h1 class="text-3xl font-bold text-gray-900">Catégorie introuvable</h1>
    <p class="mt-2 mb-8 text-gray-600">Cette catégorie n'existe pas ou a été supprimée.</p>

    <x-alert type="info" title="Que faire ?" class="max-w-2xl">
        Choisissez une catégorie ci-dessous.
    </x-alert>

    <ul class="mt-6 space-y-2">
        @forelse ($categories as $category)
            <li>
                <a href="{{ route('posts.category', $category) }}" class="font-medium text-indigo-600 hover:underline">{{ $category->name }}</a>
            </li>
        @empty
            <li class="text-gray-500">Aucune catégorie disponible.</li>
        @endforelse
    </ul>
</x-layout>
