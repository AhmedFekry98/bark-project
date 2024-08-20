<?php

namespace App\Traits;

use Modules\Auth\Entities\Role;

trait WithRoles
{
    protected $singleRole = false;

    public function getRoleAttribute(): string
    {
        return $this->roles->first()?->name ?? 'no-role';
    }

    public function getAbilitiesAttribute(): array
    {
        return $this->abilitiesFor($this->role);
    }

    public function assignRole(string $name)
    {
        try {
            $role = Role::where('name', $name)->firstOrFail();

            $this->roles()->attach($role->id);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function abilitiesFor(?string $roleName): array
    {
        if ($roleName == null) return ['no-acces'];

        $role = $this->roles()
            ->where('name', $roleName)
            ->first();

        return $role
            ? $role->abilities
            : [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
