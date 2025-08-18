<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filters
{
    
    /**
     *
     * @param  mixed  $data
     */ 
    static protected $filtersArray = [];
  
    public static function filters($data): Builder
    {

        $query = self::query();
        $filters = self::getFiltersArray();
        foreach ($data as $key => $value) {
          
            if (method_exists(static::class, 'scope' . ucfirst($key))) {
             
                $query->$key($value);
            } elseif (array_key_exists($key, $filters)) {
                
                self::{$filters[$key]}($query, $key, $value);
            }
        }

        return $query;
    }

    /**
     */
    private static function getFiltersArray(): array
    {
        if (property_exists(static::class, property: 'filtersArray')) {
            return static::$filtersArray;

        }

        return [];
    }

    /**
     *
     * @param  mixed  $query
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    private static function equal(Builder $query, $key, $value): Builder
    {
        return $query->where($key, $value);
    }

    /**
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return \Illuminate\Contracts\Database\Eloquent\Builder
     */
    private static function like(Builder $query, $key, $value): Builder
    {
        return $query->where($key, 'like', '%' . $value . '%');
    }
}
