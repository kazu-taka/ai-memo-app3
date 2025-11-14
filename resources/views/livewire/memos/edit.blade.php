<?php

use function Livewire\Volt\{state, rules, mount};
use App\Models\Memo;
use Illuminate\View\View;

state([
    'memo' => null,
    'title' => '',
    'body' => '',
]);

rules([
    'title' => 'required|max:50',
    'body' => 'required|max:2000',
]);

mount(function (Memo $memo) {
    // 認可チェック
    $this->authorize('update', $memo);

    // メモの情報を取得
    $this->memo = $memo;
    $this->title = $memo->title;
    $this->body = $memo->body;
});

$update = function () {
    // バリデーション
    $validated = $this->validate();

    // 認可チェック
    $this->authorize('update', $this->memo);

    // メモの更新
    $this->memo->update([
        'title' => $this->title,
        'body' => $this->body,
    ]);

    // フラッシュメッセージの設定
    session()->flash('message', 'メモを更新しました');

    // 詳細画面へリダイレクト
    return $this->redirect(route('memos.show', $this->memo), navigate: true);
};

$cancel = function () {
    // 詳細画面へリダイレクト
    return $this->redirect(route('memos.show', $this->memo), navigate: true);
};

?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold">メモ編集</h2>
                    </div>

                    <form wire:submit="update" class="space-y-6">
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">タイトル</label>
                            <input type="text" id="title" wire:model="title"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="body"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">本文</label>
                            <textarea id="body" wire:model="body" rows="10"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('body')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" wire:click="cancel"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                キャンセル
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
