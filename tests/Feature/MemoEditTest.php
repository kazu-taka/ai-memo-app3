<?php

use App\Models\Memo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

test('memo edit screen can be rendered', function () {
    $user = User::factory()->create();
    $memo = Memo::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->get(route('memos.edit', $memo));

    $response->assertStatus(200);
});

test('memo can be updated', function () {
    $user = User::factory()->create();
    $memo = Memo::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Volt::test('memos.edit', ['memo' => $memo])
        ->set('title', 'Updated Title')
        ->set('body', 'Updated Body Content')
        ->call('update')
        ->assertRedirect(route('memos.show', $memo));

    $this->assertDatabaseHas('memos', [
        'id' => $memo->id,
        'title' => 'Updated Title',
        'body' => 'Updated Body Content',
    ]);
});

test('memo cannot be updated by unauthorized user', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $memo = Memo::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($anotherUser)
        ->get(route('memos.edit', $memo));

    $response->assertForbidden();
});

test('memo update validation rules', function () {
    $user = User::factory()->create();
    $memo = Memo::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Volt::test('memos.edit', ['memo' => $memo])
        ->set('title', '')
        ->set('body', '')
        ->call('update')
        ->assertHasErrors(['title', 'body']);

    Volt::test('memos.edit', ['memo' => $memo])
        ->set('title', str_repeat('a', 51))
        ->set('body', str_repeat('a', 2001))
        ->call('update')
        ->assertHasErrors(['title', 'body']);
});
