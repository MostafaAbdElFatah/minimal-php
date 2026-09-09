<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

it('keeps the paginated idea index query count bounded for a large dataset', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    Idea::factory()->count(1000)->for($user)->create();
    DB::enableQueryLog();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(5);
})->group('slow', 'feature');
