<?php

use function Livewire\Volt\{mount, with, state};
use App\Models\Memo;
use Illuminate\View\View;

state(['memo' => null]);

mount(function (Memo $memo) {
    // 認可チェック
    $this->authorize('view', $memo);

    // メモの詳細情報を取得
    $this->memo = $memo;
});

$delete = function () {
    // 認可チェック
    $this->authorize('delete', $this->memo);

    // メモを削除
    $this->memo->delete();

    // 削除成功メッセージをフラッシュ
    session()->flash('message', 'メモを削除しました');

    // メモ一覧ページへリダイレクト
    return $this->redirect(route('memos.index'), navigate: true);
};

with(
    fn() => [
        'memo' => $this->memo,
    ],
);

?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">{{ $memo->title }}</h2>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $memo->created_at->format('Y年m月d日 H:i') }}
                        </div>
                    </div>

                    <div class="mb-8 whitespace-pre-wrap">{{ $memo->body }}</div>

                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('memos.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            wire:navigate>
                            戻る
                        </a>
                        <a href="{{ route('memos.edit', $memo) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 dark:hover:bg-blue-400 focus:bg-blue-500 dark:focus:bg-blue-400 active:bg-blue-700 dark:active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            wire:navigate>
                            編集
                        </a>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-400 focus:bg-red-500 dark:focus:bg-red-400 active:bg-red-700 dark:active:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            wire:click="delete" wire:confirm="このメモを削除してもよろしいですか？この操作は取り消せません。">
                            削除
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
