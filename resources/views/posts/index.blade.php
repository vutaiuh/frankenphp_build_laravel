<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Posts</h1>
        <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Create Post
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border rounded overflow-hidden">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">No</th>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($posts as $post)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm">{{ $posts->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3 text-sm font-medium">{{ $post->title }}</td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $post->status === 'publish' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm space-x-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('posts.edit', $post) }}" class="text-amber-600 hover:underline">Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete?')" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-gray-400">No posts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $posts->links() }}</div>
</div>
</body>
</html>
