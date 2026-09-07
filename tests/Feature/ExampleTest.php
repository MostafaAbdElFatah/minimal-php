<?php

test('guests are redirected to login', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get('/');
    $response->assertRedirect('/login');
});

test('authenticated users can view the home page', function () {
    /** @var \Tests\TestCase $this */
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});