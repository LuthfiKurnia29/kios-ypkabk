<?php

namespace Tests\Feature;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    public function test_login_authenticates_user_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $result = $this->userService->login([
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue($result);
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_clears_authenticated_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->userService->logout();

        $this->assertGuest();
    }

    public function test_refresh_token_regenerates_session(): void
    {
        $oldToken = session()->token();
        $this->userService->refreshToken();
        $newToken = session()->token();

        $this->assertNotEquals($oldToken, $newToken);
    }

    public function test_create_data_user_stores_user_with_role(): void
    {
        $user = $this->userService->createDataUser([
            'name' => 'Budi Santoso',
            'email' => 'Budi@example.com',
            'password' => 'budi1234',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'admin',
        ]);

        $this->assertTrue(Hash::check('budi1234', $user->password));
    }

    public function test_get_data_user_resolves_by_id_email_and_auth(): void
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        // By ID
        $foundById = $this->userService->getDataUser($user1->id);
        $this->assertEquals($user1->id, $foundById->id);

        // By Email
        $foundByEmail = $this->userService->getDataUser('user2@example.com');
        $this->assertEquals($user2->id, $foundByEmail->id);

        // Authenticated user
        $this->actingAs($user1);
        $foundAuth = $this->userService->getDataUser();
        $this->assertEquals($user1->id, $foundAuth->id);
    }

    public function test_get_users_supports_filtering_and_pagination(): void
    {
        User::factory()->create(['name' => 'Doni Admin', 'role' => 'admin']);
        User::factory()->create(['name' => 'Doni User', 'role' => 'user']);
        User::factory()->create(['name' => 'Sarah Siswa', 'role' => 'user']);

        $admins = $this->userService->getUsers(['role' => 'admin']);
        $this->assertCount(1, $admins);

        $searchDoni = $this->userService->getUsers(['search' => 'Doni']);
        $this->assertCount(2, $searchDoni);

        $paginated = $this->userService->getUsers([], 2);
        $this->assertCount(2, $paginated);
    }

    public function test_update_data_user_updates_specified_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $updated = $this->userService->updateDataUser($user->id, [
            'name' => 'New Name',
            'password' => 'newpassword123',
            'role' => 'admin',
        ]);

        $this->assertEquals('New Name', $updated->name);
        $this->assertEquals('admin', $updated->role);
        $this->assertTrue(Hash::check('newpassword123', $updated->password));
    }

    public function test_delete_data_user_removes_user(): void
    {
        $user = User::factory()->create();

        $deleted = $this->userService->deleteDataUser($user);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
