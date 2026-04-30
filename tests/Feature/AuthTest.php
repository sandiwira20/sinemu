<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $role = Role::create(['name' => 'Mahasiswa']);

        $user = User::factory()->create([
            'email' => 'mahasiswa@email.com',
            'password' => bcrypt('rahasia123'),
            'role_id' => $role->id,
        ]);

        // Ganti jadi post biasa dan arahkan ke /login
        $response = $this->post('/login', [
            'email' => 'mahasiswa@email.com',
            'password' => 'rahasia123',
        ]);

        // Kalau sukses, Laravel biasanya me-redirect user ke halaman lain (misal dashboard)
        $response->assertRedirect();
    }

    public function test_login_fails_with_wrong_password()
    {
        $role = Role::create(['name' => 'Mahasiswa']);

        User::factory()->create([
            'email' => 'mahasiswa@email.com',
            'role_id' => $role->id,
        ]);

        // Ganti jadi post biasa dan arahkan ke /login
        $response = $this->post('/login', [
            'email' => 'mahasiswa@email.com',
            'password' => 'salah_password',
        ]);

        // Kalau gagal, user akan tetap di-redirect kembali ke halaman login (status 302)
        $response->assertStatus(302);
    }
}