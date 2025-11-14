<?php

namespace Tests\Feature;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MemoDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_delete_memo(): void
    {
        // ユーザーとメモを作成
        $user = User::factory()->create();
        $memo = Memo::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Memo',
            'body' => 'This is a test memo',
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // メモ詳細ページにアクセス
        $response = $this->get(route('memos.show', $memo));
        $response->assertStatus(200);
        $response->assertSee('Test Memo');

        // メモを削除
        $response = $this->delete(route('memos.destroy', $memo));
        $response->assertRedirect(route('memos.index'));

        // メモが削除されたことを確認
        $this->assertDatabaseMissing('memos', [
            'id' => $memo->id,
        ]);
    }

    public function test_unauthorized_user_cannot_delete_memo(): void
    {
        $this->withoutExceptionHandling();

        // 2人のユーザーを作成
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // ユーザー1のメモを作成
        $memo = Memo::factory()->create([
            'user_id' => $user1->id,
            'title' => 'User 1 Memo',
            'body' => 'This memo belongs to user 1',
        ]);

        // ユーザー2としてログイン
        $this->actingAs($user2);

        // ユーザー2がユーザー1のメモを削除しようとする
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $this->delete(route('memos.destroy', $memo));

        // メモが削除されていないことを確認
        $this->assertDatabaseHas('memos', [
            'id' => $memo->id,
        ]);
    }

    public function test_guest_cannot_delete_memo(): void
    {
        // ユーザーとメモを作成
        $user = User::factory()->create();
        $memo = Memo::factory()->create([
            'user_id' => $user->id,
        ]);

        // 未認証状態でメモを削除しようとする
        $response = $this->delete(route('memos.destroy', $memo));

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect(route('login'));

        // メモが削除されていないことを確認
        $this->assertDatabaseHas('memos', [
            'id' => $memo->id,
        ]);
    }
}
