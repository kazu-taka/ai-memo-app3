<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

test('authorized users can access memo create page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('memos.create'))
        ->assertStatus(200);
});

test('guests cannot access memo create page', function () {
    $this->get(route('memos.create'))
        ->assertRedirect(route('login'));
});

test('users can create memos', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('memos.create')
        ->set('title', 'テストメモ')
        ->set('body', 'これはテスト用のメモです。')
        ->call('save')
        ->assertRedirect(route('memos.show', 1));

    $this->assertDatabaseHas('memos', [
        'user_id' => $user->id,
        'title' => 'テストメモ',
        'body' => 'これはテスト用のメモです。',
    ]);
});

test('title is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('memos.create')
        ->set('title', '')
        ->set('body', 'これはテスト用のメモです。')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

test('title cannot exceed 50 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('memos.create')
        ->set('title', str_repeat('あ', 51))
        ->set('body', 'これはテスト用のメモです。')
        ->call('save')
        ->assertHasErrors(['title' => 'max']);
});

test('body is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('memos.create')
        ->set('title', 'テストメモ')
        ->set('body', '')
        ->call('save')
        ->assertHasErrors(['body' => 'required']);
});

test('body cannot exceed 2000 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('memos.create')
        ->set('title', 'テストメモ')
        ->set('body', str_repeat('あ', 2001))
        ->call('save')
        ->assertHasErrors(['body' => 'max']);
});
