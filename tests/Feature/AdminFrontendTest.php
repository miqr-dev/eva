<?php

use App\Enums\OrganizationUnitType;
use App\Models\Course;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected from the administration area', function () {
    $this->get(route('admin.resources.index', 'kurse'))
        ->assertRedirectToRoute('login');
});

test('authorized users can open German administration pages', function () {
    $user = User::factory()->create(['organization_unit_id' => null]);
    $permission = Permission::query()->create([
        'name' => 'courses.manage',
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permission);
    $state = OrganizationUnit::factory()->create([
        'name' => 'Thüringen',
        'type' => OrganizationUnitType::Institution,
    ]);
    $organizationUnit = OrganizationUnit::factory()->for($state, 'parent')->create([
        'name' => 'Erfurt',
        'type' => OrganizationUnitType::Department,
    ]);
    $course = Course::factory()->for($organizationUnit)->create();

    $this->actingAs($user)
        ->get(route('admin.resources.index', 'kurse'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/ResourceIndex')
            ->where('resourceKey', 'kurse')
            ->has('records', 1)
            ->where('records.0.id', $course->id)
            ->where('options.organizationUnits.0.label', 'Thüringen')
            ->where('options.organizationUnits.0.options.0.value', $organizationUnit->id)
            ->where('options.organizationUnits.0.options.0.label', 'Erfurt')
            ->where('options.organizationUnitParents.0.value', $state->id)
            ->where('options.organizationUnitParents.0.label', 'Thüringen')
        );
});

test('users without permission cannot open an administration page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.resources.index', 'benutzer'))
        ->assertForbidden();
});

test('unknown administration resources return not found', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/verwaltung/unbekannt')
        ->assertNotFound();
});
