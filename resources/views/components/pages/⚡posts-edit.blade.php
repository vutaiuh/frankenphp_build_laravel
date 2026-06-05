<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new class extends Component
{
     #[Title('Posts Edit Page')]
    public int $id;

    #[Validate('required|min:3')]
    public string $title = '';

    #[Validate('required|min:5')]
    public string $body  = '';

    // ← nhận route parameter {id} tự động qua mount()
    public function mount(int $id): void
    {
        $this->id = $id;

        // Fake: giả lập find by id
        $fake = [
            1 => ['title' => 'Bài 1', 'body' => 'Nội dung 1'],
            2 => ['title' => 'Bài 2', 'body' => 'Nội dung 2'],
        ];

        $post = $fake[$id] ?? abort(404);

        $this->title = $post['title'];
        $this->body  = $post['body'];
    }

    public function update(): void
    {
        $this->validate();

        // TODO: Post::findOrFail($this->id)->update(...)
        session()->flash('success', 'Cập nhật thành công!');

        $this->redirect('/posts', navigate: true);
    }
};
?>

<div class="max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Post #{{ $id }}</h1>

    <div class="bg-white border rounded-xl p-6 shadow-sm">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Title</label>
            <input wire:model="title" type="text"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none
                          @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Body</label>
            <textarea wire:model="body" rows="4"
                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none
                             @error('body') border-red-400 @enderror"></textarea>
            @error('body') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button wire:click="update"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Update
            </button>
            <a href="/posts" wire:navigate
               class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </div>
</div>
