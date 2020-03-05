<?php
namespace Arni\CustomSort\Traits;

use Arni\CustomSort\Models\CustomSort;

/**
 * Trait for Models to add the capability of having custom sort on any model.
 */
trait HasCustomSortTrait
{
    /**
     * Get custom sort records.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function customSort()
    {
        return $this->morphMany(CustomSort::class, 'sortable');
    }

    /**
     * Scope to order by custom sort
     * Records will be returned in order of decreasing priority from custom_sorts table,
     * any references not present in custom_sorts, will be added at the end
     * @param  [QueryBuilder] $query
     * @return void
     */
    public function scopeOrderByCustomSort($query)
    {
        $tableName = $this->getTable();
        $primaryKey = $this->getKeyName();
        $morphClass = $this->customSort()->getMorphClass();

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if($driver == 'mysql'){
            $morphClass = str_replace("\\", "\\\\", $morphClass);
        }
//        dd($morphClass);
        $subQuery = CustomSort::select('priority')
          ->whereRaw("$tableName.$primaryKey = custom_sorts.sortable_id AND custom_sorts.sortable_type = '$morphClass' ");
        $query->orderByRaw("({$subQuery->limit(1)->toSql()}) DESC");
    }

    /**
     * Custom sort with fallback to DB orderBy
     * @param $query
     * @param $orderBy
     * @param $sort
     */
    public function scopeOrderByWithCustomSort($query, $orderBy, $sort = 'asc')
    {
        $query->when($orderBy == 'custom_sort', function ($query) {
            $query->orderByCustomSort();
        }, function ($query) use ($orderBy,$sort) {
            $query->orderBy($orderBy, $sort);
        });
    }
}
