<?php

use App\Models\Permission;
use App\Models\Subject;
use App\Models\User;

function subjectManager(): User
{
    $user = User::factory()->create();
    $permission = Permission::query()->firstOrCreate([
        'name' => 'courses.manage',
        'guard_name' => 'web',
    ]);
    $user->permissions()->attach($permission);

    return $user;
}

test('authorized users can create a subject', function () {
    $user = subjectManager();

    $this->actingAs($user)->postJson(route('admin.api.subjects.store'), [
        'name' => 'Büroorganisation',
        'code' => 'BUERO',
    ])->assertCreated()
        ->assertJsonPath('data.name', 'Büroorganisation')
        ->assertJsonPath('data.code', 'BUERO')
        ->assertJsonPath('data.is_active', true);

    $this->assertDatabaseHas('subjects', [
        'name' => 'Büroorganisation',
        'code' => 'BUERO',
    ]);
});

test('subject names must be unique', function () {
    $user = subjectManager();
    Subject::factory()->create(['name' => 'Werkstoffwirtschaft']);

    $this->actingAs($user)->postJson(route('admin.api.subjects.store'), [
        'name' => 'Werkstoffwirtschaft',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

test('authorized users can update and delete a subject', function () {
    $user = subjectManager();
    $subject = Subject::factory()->create(['name' => 'Alt', 'is_active' => true]);

    $this->actingAs($user)->putJson(route('admin.api.subjects.update', $subject), [
        'name' => 'Neu',
        'is_active' => false,
    ])->assertOk()
        ->assertJsonPath('data.name', 'Neu')
        ->assertJsonPath('data.is_active', false);

    $this->actingAs($user)->deleteJson(route('admin.api.subjects.destroy', $subject))
        ->assertNoContent();

    $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
});

test('deleting a subject leaves existing course-teacher assignments in place without a subject', function () {
    $user = subjectManager();
    $subject = Subject::factory()->create();
    $teacher = \App\Models\Teacher::factory()->create();
    $course = \App\Models\Course::factory()->create();

    app(\App\Services\CourseTeacherAssignmentSyncService::class)->syncForCourse($course, [
        ['teacher_id' => $teacher->id, 'subject_id' => $subject->id],
    ]);

    $this->actingAs($user)->deleteJson(route('admin.api.subjects.destroy', $subject))
        ->assertNoContent();

    $this->assertDatabaseHas('course_teacher', [
        'course_id' => $course->id,
        'teacher_id' => $teacher->id,
        'subject_id' => null,
    ]);
});
