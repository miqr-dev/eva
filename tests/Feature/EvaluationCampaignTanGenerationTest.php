<?php

use App\Enums\EvaluationCampaignStatus;
use App\Models\EvaluationCampaign;
use App\Models\Permission;
use App\Models\Tan;
use App\Models\User;
use App\Services\TanService;
use Inertia\Testing\AssertableInertia as Assert;

function tanCampaignManager(): User
{
    $user = User::factory()->create();
    $permission = Permission::query()->firstOrCreate([
        'name' => 'campaigns.manage',
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permission);

    return $user;
}

test('authorized users can open the tan generator page', function () {
    $user = tanCampaignManager();
    $campaign = EvaluationCampaign::factory()->create([
        'title' => 'Deutsch B2 Kursbewertung',
        'status' => EvaluationCampaignStatus::Scheduled,
    ]);

    Tan::query()->create([
        'evaluation_campaign_id' => $campaign->id,
        'tan_code_hash' => app(TanService::class)->hash('A8K9-PQ22'),
        'is_active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('admin.evaluation-campaigns.tans.show', $campaign))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/EvaluationTans')
            ->where('campaign.id', $campaign->id)
            ->where('campaign.title', 'Deutsch B2 Kursbewertung')
            ->where('tanStats.total', 1)
            ->where('tanStats.unused', 1)
        );
});

test('authorized users can generate tans for an evaluation campaign', function () {
    $user = tanCampaignManager();
    $campaign = EvaluationCampaign::factory()->create([
        'status' => EvaluationCampaignStatus::Active,
        'ends_at' => now()->addWeek(),
    ]);

    $response = $this->actingAs($user)->postJson(
        route('admin.api.evaluation-campaigns.tans.store', $campaign),
        ['amount' => 3],
    );

    $response
        ->assertCreated()
        ->assertJsonCount(3, 'tans')
        ->assertJsonPath('stats.total', 3)
        ->assertJsonPath('stats.unused', 3);

    $codes = $response->json('tans');
    expect($codes)->toHaveCount(3);

    foreach ($codes as $code) {
        expect($code)->toMatch('/^[A-Z2-9]{4}-[A-Z2-9]{4}$/');

        $this->assertDatabaseMissing('tans', [
            'tan_code_hash' => $code,
        ]);
        $this->assertDatabaseHas('tans', [
            'evaluation_campaign_id' => $campaign->id,
            'tan_code_hash' => app(TanService::class)->hash($code),
        ]);
    }

    $storedTan = Tan::query()->firstOrFail();

    expect($storedTan->expires_at?->toDateTimeString())
        ->toBe($campaign->ends_at?->toDateTimeString());
});

test('tan generation is blocked for closed campaigns', function () {
    $user = tanCampaignManager();
    $campaign = EvaluationCampaign::factory()->create([
        'status' => EvaluationCampaignStatus::Closed,
    ]);

    $this->actingAs($user)->postJson(
        route('admin.api.evaluation-campaigns.tans.store', $campaign),
        ['amount' => 1],
    )->assertUnprocessable()
        ->assertJsonValidationErrors('amount');

    expect(Tan::query()->count())->toBe(0);
});

test('users without campaign permission cannot manage tans', function () {
    $user = User::factory()->create();
    $campaign = EvaluationCampaign::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.evaluation-campaigns.tans.show', $campaign))
        ->assertForbidden();

    $this->actingAs($user)->postJson(
        route('admin.api.evaluation-campaigns.tans.store', $campaign),
        ['amount' => 1],
    )->assertForbidden();
});
