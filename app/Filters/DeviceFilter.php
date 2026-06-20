<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DeviceFilter
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

    /**
     * @param mixed $value
     */
    protected function status($value): void
    {
        $this->query->where('tracker_status', $value);
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
    protected function search($value): void
    {
        $this->query->where(function ($query) use ($value) {
            $query
                ->where('name', 'ilike', "%{$value}%")
                ->orWhere('system_no', 'ilike', "%{$value}%")
                ->orWhere('imei', 'ilike', "%{$value}%");
        });
    }
}
