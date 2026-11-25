<x-layout>
    <x-slot:title>Utilisateurs - Mini-CMS</x-slot:title>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Utilisateurs</h1>
        <p class="mt-2 text-sm text-gray-600">Changez le rôle d'un compte : auteur ou administrateur.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 text-gray-600">
                <tr>
                    <th scope="col" class="py-2 pr-4 font-medium">Nom</th>
                    <th scope="col" class="py-2 pr-4 font-medium">E-mail</th>
                    <th scope="col" class="py-2 pr-4 font-medium">Rôle</th>
                    <th scope="col" class="py-2 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-100 align-top">
                        <td class="py-3 pr-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="py-3 pr-4 text-gray-600">{{ $user->email }}</td>
                        <td class="py-3 pr-4 text-gray-600">{{ $user->role === 'admin' ? 'Administrateur' : 'Auteur' }}</td>
                        <td class="py-3">
                            @if ($user->id === auth()->id())
                                <span class="text-sm text-gray-500">Votre propre compte</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <label class="sr-only" for="role-{{ $user->id }}">Rôle de {{ $user->name }}</label>
                                    <select id="role-{{ $user->id }}" name="role" class="rounded-md border-gray-300 text-sm">
                                        <option value="author" @selected($user->role === 'author')>Auteur</option>
                                        <option value="admin" @selected($user->role === 'admin')>Administrateur</option>
                                    </select>

                                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Changer le rôle</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500">Aucun utilisateur pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
