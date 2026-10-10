<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
class DashboardController extends Controller {
 public function index(){
  $totalProducts=Product::count();$totalCategories=Category::count();
  $inventoryValue=Product::selectRaw('COALESCE(SUM(stock * price),0) total')->value('total');
  $lowStock=Product::whereColumn('stock','<=','minimum_stock')->count();
  $recent=StockMovement::with(['product','user'])->latest()->take(7)->get();
  $lowProducts=Product::with('category')->whereColumn('stock','<=','minimum_stock')->orderBy('stock')->take(6)->get();
  return view('dashboard',compact('totalProducts','totalCategories','inventoryValue','lowStock','recent','lowProducts'));
 }
}
