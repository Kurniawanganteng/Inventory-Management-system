<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model {
 protected $fillable=['category_id','sku','name','price','stock','minimum_stock'];
 protected $casts=['price'=>'decimal:2','stock'=>'integer','minimum_stock'=>'integer'];
 public function category(): BelongsTo { return $this->belongsTo(Category::class); }
 public function suppliers(): BelongsToMany { return $this->belongsToMany(Supplier::class)->withPivot('supplier_price'); }
 public function stockMovements(): HasMany { return $this->hasMany(StockMovement::class); }
}
