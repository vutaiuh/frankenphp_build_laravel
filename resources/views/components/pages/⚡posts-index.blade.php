<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new class extends Component
{
     #[Title('Posts Page')]
    public array $posts = [];

    public function mount(): void
    {
        $this->posts = [
            ['id' => 1, 'title' => 'Bài 1', 'body' => 'Nội dung 1'],
            ['id' => 2, 'title' => 'Bài 2', 'body' => 'Nội dung 2'],
        ];
    }

    public function delete(int $id): void
    {
        $this->posts = array_values(
            array_filter($this->posts, fn($p) => $p['id'] !== $id)
        );
    }
};
?>

<div class="max-w-2xl mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Posts</h1>
        <a href="/posts/create" wire:navigate
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + New Post
        </a>
    </div>

    <div class="space-y-3">
        @forelse($posts as $post)
            <div class="bg-white border rounded-xl p-4 flex justify-between items-start shadow-sm">
                <div>
                    <h3 class="font-semibold">{{ $post['title'] }}</h3>
                    <p class="text-gray-500 text-sm">{{ $post['body'] }}</p>
                </div>
                <div class="flex gap-3 ml-4 shrink-0 text-sm">
                    <a href="/posts/{{ $post['id'] }}/edit" wire:navigate
                       class="text-blue-500 hover:underline">Edit</a>
                    <button wire:click="delete({{ $post['id'] }})"
                            wire:confirm="Xoá bài này?"
                            class="text-red-500 hover:underline">Delete</button>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 py-10">Chưa có bài viết nào.</p>
        @endforelse
    </div>
</div>
