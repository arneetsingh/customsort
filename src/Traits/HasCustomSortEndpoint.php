<?php
namespace Arni\CustomSort\Traits;

use Illuminate\Http\Request;
use Arni\CustomSort\Models\CustomSort;
use Illuminate\Support\Facades\Validator;

/**
 * Trait for controller to handle  PUT `/models/customSort` endpoint
 * NOTE: Using controller needs have a proterty of transformer used for its model
 * and that transformer must have model as typehinted in transform method
 */
trait HasCustomSortEndpoint
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateCustomSort(Request $request)
    {
        // check if controller has transformer
        if (!property_exists($this, 'model')) {
            throw new \Exception("Model property not defined on controller");
        }

        $modelClass = $this->model;

        // get table name and primary key from modelclass
        $tableName = (new $modelClass)->getTable();
        $primaryKey = (new $modelClass)->getKeyName();

        $validator = Validator::make($request->all(), [
            'custom_sort' => 'required',
            'custom_sort.*.id' => "required|exists:$tableName,$primaryKey",
            'custom_sort.*.priority' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        // get the morphclass name, eg. "carriers" from Relation::morphMap in App service provider
        $morphClass = (new $modelClass)->customSort()->getMorphClass();
        // delete all entries for this type of model
        CustomSort::where('sortable_type', $morphClass)->delete();
        // insert custom sort records
        collect($request->custom_sort)->transform(function ($item) use ($morphClass) {
            CustomSort::create([
            'sortable_id' => $item['id'],
            'sortable_type' => $morphClass,
            'priority' => $item['priority']
          ]);
        });
        // return response
        return response()->json(['message' => 'Request successfully completed']);
    }
}
