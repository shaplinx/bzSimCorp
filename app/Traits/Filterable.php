<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Contracts\FilterableContract;

trait Filterable
{

    
    /**
     * Apply a dynamic search filter across multiple fields.
     */
    public function scopeSearch($search, array $columns = []): self
    {
        if (!$this instanceof Searchable) {
            throw new \LogicException(get_class($this) . " must implement Searchable interface.");
        }
        
        if (!empty($search) && !empty($columns)) {
            $this->where(function (Builder $q) use ($search, $columns) {
                foreach ($columns as $column) {
                    // Use `whereDate` if searching a date column
                    if (str_contains($column, 'date') || str_contains($column, 'at')) {
                        $q->orWhereDate($column, $search);
                    } else {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                }
            });
        }

        return $this;
    }
}
