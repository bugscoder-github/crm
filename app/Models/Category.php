<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;

    // protected $attributes = [
    //     'id' => null,
    //     'parent_id' => null,
    //     'name' => null,
    // ];

    // protected $fillable = [
    //     'id',
    //     'parent_id',
    //     'name',
    // ];

    protected $attributes = [];
    protected $fillable = [];

    public function __construct() {
        $this->attributes = [
            'id' => null,
            'parent_id' => 0,
            'name' => null
        ];

        $this->fillable = array_keys($this->attributes);
    }

    public static function getCategoryList() {
        $categories = Category::all()->toArray();

        $hierarchy   = [];
        $categoryMap = [];
        
        foreach ($categories as $category) {
            $category['subcategories']    = [];
            $categoryMap[$category['id']] = $category;
        }

        foreach ($categories as $category) {
            if ($category['parent_id'] === null) {
                $hierarchy[] = &$categoryMap[$category['id']];
                continue;
            }
    
            if (isset($categoryMap[$category['parent_id']])) {
                $categoryMap[$category['parent_id']]['subcategories'][] = &$categoryMap[$category['id']];
            } else {
                $hierarchy[] = &$categoryMap[$category['id']];
            }
        }
    
        return $hierarchy;
    }

    public static function generateOptions() {
        $categories = self::getCategoryList();
        
        $options = [];
        $stack = [];
    
        // Initialize the stack with the top-level categories
        foreach ($categories as $category) {
            $stack[] = ['category' => $category, 'depth' => 0];
        }
    
        while (!empty($stack)) {
            // Pop the last element from the stack
            $current = array_pop($stack);
            $category = $current['category'];
            $depth = $current['depth'];
    
            // Generate the indent
            $indent = str_repeat('-', $depth * 2);
            if ($indent != "") { $indent .= " "; }
    
            // Add the current category to the options array
            $options[] = [
                'id' => $category['id'],
                'name' => $indent . $category['name']
            ];
    
            // Add subcategories to the stack with increased depth
            if (!empty($category['subcategories'])) {
                foreach (array_reverse($category['subcategories']) as $subcategory) {
                    $stack[] = ['category' => $subcategory, 'depth' => $depth + 1];
                }
            }
        }
    
        return $options;
    }
}
