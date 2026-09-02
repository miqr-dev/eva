<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class TeacherRole extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherRoleFactory> */
    use HasFactory;

    /** @return HasMany<Teacher, $this> */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /** @return HasMany<ModuleVersion, $this> */
    public function moduleVersions(): HasMany
    {
        return $this->hasMany(ModuleVersion::class, 'target_role_id');
    }
}
