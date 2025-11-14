<?php
// tests/Unit/UserTest.php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserComponentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * ユーザーを作成できることを確認
     */
    public function it_can_create_a_user()
    {
        // Arrange (準備)
        $userData = [
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
            'password' => Hash::make('password123')
        ];

        // Act (実行)
        $user = User::create($userData);

        // Assert (検証)
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('田中太郎', $user->name);
        $this->assertDatabaseHas('users', [
            'email' => 'tanaka@example.com'
        ]);
    }

    /**
     * @test
     * パスワードが自動的にハッシュ化されることを確認
     */
    public function it_automatically_hashes_password()
    {
        // Arrange
        $password = 'plaintext_password';
        $user = User::create([
            'name' => '山田花子',
            'email' => 'yamada@example.com',
            'password' => Hash::make($password)
        ]);

        // Assert
        $this->assertNotEquals($password, $user->password);
        $this->assertTrue(Hash::check($password, $user->password));
    }
    
    /**
     * @test
     * メールアドレスが必須であることを確認
     */
    public function it_requires_email_address()
    {
        // メールアドレスなしでユーザーを作成しようとすると例外が発生
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::create([
            'name' => 'テストユーザー',
            'password' => Hash::make('password123')
            // email が無い
        ]);
    }
}
