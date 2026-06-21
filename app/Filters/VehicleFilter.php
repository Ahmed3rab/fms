<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VehicleFilter
{
    /**
     * @param array<int,mixed> $filters
     */
    public function __construct(protected Builder $query, protected array $filters) {}

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

    /**
     * @param mixed $value
     */
    protected function company($value): void
    {
        $this->query->where('company_id', $value);
    }

    protected function brand(mixed $value): void
    {
        $this->query->where('brand', $value);
    }

    /**
     * @param mixed $value
     */
    protected function model($value): void
    {
        $this->query->where('model', $value);
    }

    /**
     * @param mixed $value
     */
    protected function plateNumber($value): void
    {
        $this->query->where('plate_number', $value);
    }

    protected function search(mixed $value): void
    {
        $this->query->where(function (Builder $query) use ($value) {
            $query
                ->where('plate_number', 'ilike', "%{$value}%")
                ->orWhere('brand', 'ilike', "%{$value}%")
                ->orWhere('model', 'ilike', "%{$value}%")
                ->orWhere('engine_number', 'ilike', "%{$value}%")
                ->orWhere('chassis_number', 'ilike', "%{$value}%");
        });
    }
}
