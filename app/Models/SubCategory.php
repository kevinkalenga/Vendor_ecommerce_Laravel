<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
      protected $guarded = [];

      public function category()
      {
            // subcategory belongs to the category
            return $this->belongsTo(Category::class, 'category_id', 'id');
      }
}
