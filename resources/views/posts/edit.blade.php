<!DOCTYPE html>
<html><head><title>Edit Post</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6">Edit Post</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Title</label>
            <input name="title" value="{{ old('title', $post->title) }}" type="text"
                class="w-full border rounded px-3 py-2 @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Content</label>
            <textarea name="content" rows="5"
                class="w-full border rounded px-3 py-2">{{ old('content', $post->content) }}</textarea>
            @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="publish" {{ old('status', $post->status) === 'publish' ? 'selected' : '' }}>Publish</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="{{ route('posts.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
</body></html>
