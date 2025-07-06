<?php

namespace App\Rules;

use App\Models\AffectationVehicule;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class NoOverlappingAffectation implements Rule, DataAwareRule
{
    private array $data = [];

    public function __construct(private int $vehiculeId, private ?int $ignoreId = null) {}

    /**
     * Set the data under validation.
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value): bool
    {
        // attribute looks like affectations.*.date_debut or date_fin
        // Determine index
        // We'll only validate on date_debut attribute
        if (!str_contains($attribute, '.date_debut')) {
            return true;
        }

        $index = (int)explode('.', $attribute)[1];
        $start = $value;
        $end   = $this->data['affectations'][$index]['date_fin'] ?? null;

        // Check overlap in DB for same vehicule (excluding current affectation id)
        $query = AffectationVehicule::where('vehicule_id', $this->vehiculeId)
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q2) use ($start, $end) {
                    $q2->where('date_debut', '<=', $start)
                        ->where(function ($q3) use ($start, $end) {
                            $q3->whereNull('date_fin')
                                ->orWhere('date_fin', '>=', $start);
                        });
                });
                if ($end) {
                    $q->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('date_debut', '<=', $end)
                            ->where(function ($q3) use ($start, $end) {
                                $q3->whereNull('date_fin')
                                    ->orWhere('date_fin', '>=', $end);
                            });
                    });
                }
            });
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }
        $existsOverlap = $query->exists();

        // Also check within same payload to see duplicates
        foreach ($this->data['affectations'] as $i => $aff) {
            if ($i === $index) continue;
            if (($aff['date_debut'] <= $start && (empty($aff['date_fin']) || $aff['date_fin'] >= $start)) ||
                ($end && $aff['date_debut'] <= $end && (empty($aff['date_fin']) || $aff['date_fin'] >= $end))) {
                return false;
            }
        }

        return ! $existsOverlap;
    }

    public function message(): string
    {
        return 'Il existe déjà une affectation de véhicule qui chevauche ces dates.';
    }
}