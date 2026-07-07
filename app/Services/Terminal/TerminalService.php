<?php

namespace App\Services\Terminal;

use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class TerminalService
{
    /** @return Collection<int, Terminal> */
    public function active(): Collection
    {
        return Terminal::query()->active()->ordered()->get();
    }

    /** @return array<int, mixed> */
    public function idValidationRules(bool $required = true): array
    {
        $rules = [$required ? 'required' : 'nullable', 'integer'];

        $rules[] = Rule::exists('terminals', 'id')->where('is_active', true);

        return $rules;
    }

    public function hasActive(): bool
    {
        return Terminal::query()->active()->exists();
    }

    /** Terminals the current user may assign bus stands to. */
    public function selectableFor(?User $user = null): Collection
    {
        $user ??= auth()->user();

        if ($user?->isTerminalAdmin()) {
            return $user->ownedTerminals()->active()->ordered()->get();
        }

        return $this->active();
    }

    /** @return array<int, mixed> */
    public function idValidationRulesFor(?User $user = null, bool $required = true): array
    {
        $user ??= auth()->user();
        $rules = [$required ? 'required' : 'nullable', 'integer'];

        if ($user?->isTerminalAdmin()) {
            $ids = $user->ownedTerminals()->active()->pluck('id')->all();
            if ($ids !== []) {
                $rules[] = Rule::in($ids);
            }
        } else {
            $rules[] = Rule::exists('terminals', 'id')->where('is_active', true);
        }

        return $rules;
    }
}
