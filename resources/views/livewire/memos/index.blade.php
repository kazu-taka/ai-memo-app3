<?php

use function Livewire\Volt\{with};
use App\Models\Memo;
use Illuminate\View\View;

with(
    fn() => [
        'memos' => auth()->user()->memos()->latest()->get(),
    ],
);

?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">メモ一覧</h2>
                        <a href="{{ route('memos.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            wire:navigate>
                            新規作成
                        </a>
                    </div>

                    @if ($memos->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">メモがありません。</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($memos as $memo)
                                <a href="{{ route('memos.show', $memo) }}"
                                    class="block border dark:border-gray-700 p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                    wire:navigate>
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-medium">{{ $memo->title }}</h3>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $memo->created_at->format('Y/m/d H:i') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
