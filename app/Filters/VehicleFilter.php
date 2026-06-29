<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Applies query string filters to the Vehicle listing endpoint.
 *
 * Supported filters:
 *
 * - uuid
 * - company
 * - brand
 * - model
 * - plate_number
 * - tracked
 * - search
 * - sort
 */
class VehicleFilter
{
    /**
     * @var list<string>
     */
    protected const SORTABLE_COLUMNS = [
        'plate_number',
        'brand',
        'model',
        'created_at',
    ];

    /**
     * @param array<int,mixed> $filters
     * @param Builder<Model> $query
     */
    public function __construct(protected Builder $query, protected array $filters) {}

    /**
     * @return Builder<Model>
     */
    public function apply(): Builder
    {
        foreach ($this->filters as $name => $value) {
            if (
                method_exists($this, $name)
                && $value !== null
                && $value !== ''
            ) {
                $this->$name($value);
            }
        }

        return $this->query;
    }

    protected function uuid(mixed $value): void
    {
        $this->query->whereUuid(trim($value));
    }

    /**
     * @param mixed $value
     */
    protected function company(mixed $value): void
    {
        $this->query->whereHas('company', fn(Builder $query) => $query->whereUuid(trim($value)));
    }

    protected function company_name(mixed $value): void
    {
        $this->query->whereHas(
            'company',
            fn(Builder $query) => $query->where(
                'name',
                'ilike',
                '%' . trim($value) . '%',
            ),
        );
    }

    protected function brand(mixed $value): void
    {
        $this->query->where('brand', trim($value));
    }

    /**
     * @param mixed $value
     */
    protected function model($value): void
    {
        $this->query->where('model', trim($value));
    }

    /**
     * @param mixed $value
     */
    protected function plate_number($value): void
    {
        $this->query->where('plate_number', trim($value));
    }

    protected function search(mixed $value): void
    {
        $value = trim($value);

        $this->query->where(function (Builder $query) use ($value) {
            $query
                ->where('plate_number', 'ilike', "%{$value}%")
                ->orWhere('brand', 'ilike', "%{$value}%")
                ->orWhere('model', 'ilike', "%{$value}%")
                ->orWhere('engine_number', 'ilike', "%{$value}%")
                ->orWhere('chassis_number', 'ilike', "%{$value}%")
                ->orWhereHas(
                    'company',
                    fn(Builder $company) => $company->where(
                        'name',
                        'ilike',
                        "%{$value}%"
                    ),
                );
        });
    }

    protected function tracked(mixed $value): void
    {
        $tracked = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($tracked === null) {
            return;
        }

        $tracked ? $this->query->has('device') : $this->query->doesntHave('device');
    }


    protected function sort(mixed $value): void
    {
        $value = trim($value);

        $direction = str_starts_with($value, '-') ? 'desc' : 'asc';

        $column = ltrim($value, '-');

        if (! in_array($column, self::SORTABLE_COLUMNS, true)) {
            return;
        }

        $this->query->orderBy(
            $column,
            $direction,
        );
    }
}
