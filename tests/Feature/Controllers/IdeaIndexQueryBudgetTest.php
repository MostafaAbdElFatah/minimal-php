<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Support\Facades\DB;

covers(IdeaController::class);

it('keeps the paginated index within a fixed query budget regardless of dataset size', function () {
    $user = User::factory()->create();
    Idea::factory()->count(100)->for($user)->create();
    DB::enableQueryLog();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    // one count + one page of ideas, plus the auth lookup and session bookkeeping
    expect(DB::getQueryLog())->toHaveCount(count(DB::getQueryLog()))->and(count(DB::getQueryLog()))->toBeLessThanOrEqual(4);
})->group('feature', 'controllers');
