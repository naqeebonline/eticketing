<?php

namespace App\Services\City;

use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CityService
{
    /** @return Collection<int, City> */
    public function active(): Collection
    {
        return City::query()->active()->ordered()->get();
    }

    /** @return Collection<int, string> */
    public function activeNames(): Collection
    {
        return $this->active()->pluck('name');
    }

    /** @return array<int, mixed> */
    public function nameValidationRules(bool $required = true): array
    {
        $names = $this->activeNames()->all();

        $rules = [$required ? 'required' : 'nullable', 'string', 'max:100'];

        if ($names !== []) {
            $rules[] = Rule::in($names);
        }

        return $rules;
    }

    public function isActiveName(string $name): bool
    {
        return City::query()
            ->active()
            ->where('name', $name)
            ->exists();
    }
}
