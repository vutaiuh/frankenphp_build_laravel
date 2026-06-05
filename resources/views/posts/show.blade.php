<!DOCTYPE html>
<html><head><title>{{ $post->title }}</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <span class="px-2 py-1 rounded-full text-xs font-semibold
            {{ $post->status === 'publish' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            {{ ucfirst($post->status) }}
        </span>
        <a href="{{ route('posts.index') }}" class="text-gray-500 hover:underline text-sm">← Back</a>
    </div>
    <h1 class="text-2xl font-bold mb-4">{{ $post->title }}</h1>
    <p class="text-gray-700 leading-relaxed">{{ $post->content }}</p>
</div>
</body></html>
