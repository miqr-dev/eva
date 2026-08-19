<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['organization_unit_id', 'name', 'email', 'password', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** @return BelongsTo<OrganizationUnit, $this> */
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    /** @return HasOne<Teacher, $this> */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /** @return BelongsToMany<Role, $this> */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')
            ->withTimestamps();
    }

    /** @return BelongsToMany<Permission, $this> */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission')
            ->withTimestamps();
    }

    /** @return HasMany<QuestionnaireTemplate, $this> */
    public function createdQuestionnaireTemplates(): HasMany
    {
        return $this->hasMany(QuestionnaireTemplate::class, 'created_by_id');
    }

    /** @return HasMany<Module, $this> */
    public function createdModules(): HasMany
    {
        return $this->hasMany(Module::class, 'created_by_id');
    }

    /** @return HasMany<EvaluationCampaign, $this> */
    public function createdEvaluationCampaigns(): HasMany
    {
        return $this->hasMany(EvaluationCampaign::class, 'created_by_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->where('guard_name', 'web')
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->where('guard_name', 'web')
            ->exists()
            || $this->roles()
                ->whereHas('permissions', function ($query) use ($permission): void {
                    $query
                        ->where('name', $permission)
                        ->where('guard_name', 'web');
                })
                ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
