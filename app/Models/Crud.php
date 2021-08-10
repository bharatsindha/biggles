<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Crud extends Model
{

    /**
     * To generate the query for  global search
     *
     * @param array $options
     * @return Model|int|object|null
     */
    public function globalSearch($options = [])
    {
        $query = $this->_getQuery($options);

        if (isset($options['groupBy'])) {
            // Get default order
            $query->groupBy($options['groupBy']);
        }

        if (isset($options['orderBy'])) {
            // Get default order
            $order = isset($options['order']) ? $options['order'] : 'ASC';
            $query->orderBy($options['orderBy'], $order);
        }
        if (isset($options['whereNot'])) {
            // where not condition
            if (is_array($options['whereNot'])) {
                foreach ($options['whereNot'] as $key => $value) {
                    $query->where($key, '!=', $value);
                }
            }
        }

        // Where In condition
        if (isset($options['whereIn'])) {
            if (is_array($options['whereIn'])) {
                foreach ($options['whereIn'] as $key => $value) {
                    $query->whereIn($key, $value);
                }
            }
        }

        // Where Not In condition
        if (isset($options['whereNotIn'])) {
            if (is_array($options['whereNotIn'])) {
                foreach ($options['whereNotIn'] as $key => $value) {
                    $query->whereNotIn($key, $value);
                }
            }
        }

        // Where not null condition
        if (isset($options['whereNotNull'])) {
            if (is_array($options['whereNotNull'])) {
                foreach ($options['whereNotNull'] as $value) {
                    $query->whereNotNull($value);
                }
            } else {
                $query->whereNotNull($options['whereNotNull']);
            }
        }

        // Where null condition
        if (isset($options['whereNull'])) {
            if (is_array($options['whereNull'])) {
                foreach ($options['whereNull'] as $key => $value) {
                    $query->whereNull($key, $value);
                }
            } else {
                $query->whereNull($options['whereNull']);
            }
        }

        // Where condition
        if (isset($options['where'])) {
            if (is_array($options['where'])) {
                foreach ($options['where'] as $key => $value) {
                    $sign = "=";
                    if (strpos($key, " ") !== false) {
                        $parts = explode(" ", $key);
                        $sign  = $parts[1];
                        $key   = $parts[0];
                    }
                    $query->where($key, $sign, $value);
                }

            } else {
                $query->where($options['where']);
            }
        }

        if (isset($options['withtrashed'])) {
            $query->withTrashed();
        }

        // keyword search condition
        if (isset($options['match']) && count($options['match'])) {
            $matches = $options['match'];
            $query->where(function ($query) use ($matches, $options) {
                foreach ($matches as $key => $value) {
                    $query->orWhere($key, 'like', "%" . $value . "%");
                }

                if (isset($options['matchRaw']) && is_array($options['matchRaw'])) {

                    $matchRaws = $options['matchRaw'];
                    foreach ($matchRaws as $key => $value) {
                        $query->orWhereRaw("$key like  '%" . $value . "%'");
                    }
                }
            });
        }
        // limit clause
        if (isset($options['limit'])) {
            $query->limit($options['limit']);
        }
        // offset for pagination
        if (isset($options['offset'])) {
            $query->offset($options['offset']);
        }

        return $query;
    }

    /**
     * Generate the query from the module
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getQuery($options = [])
    {
        return parent::query();
    }

}
