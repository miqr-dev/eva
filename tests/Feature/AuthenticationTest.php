<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('home'))
        ->assertRedirectToRoute('login');

    $this->get(route('dashboard'))
        ->assertRedirectToRoute('login');
});

test('the login page is displayed', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/Login')
            ->missing('demoEmail')
        );
});

test('active users can authenticate', function () {
    $user = User::factory()->create([
        'email' => 'ara.matoyan@miqr.de',
        'password' => '123qwe!',
        'is_active' => true,
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => '123qwe!',
    ])->assertRedirectToRoute('dashboard');

    $this->assertAuthenticatedAs($user);
});

test('inactive users cannot authenticate', function () {
    $user = User::factory()->create([
        'password' => 'password',
        'is_active' => false,
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('authenticated users can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('logout'))
        ->assertRedirectToRoute('login');

    $this->assertGuest();
});
