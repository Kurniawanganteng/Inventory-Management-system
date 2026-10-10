<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProductController extends Controller {
 public function index(Request $request) {
  $sort=in_array($request->query('sort'),['name','sku','price','stock'])?$request->query('sort'):'name';
  $direction=$request->query('direction')==='desc'?'desc':'asc';
  $products=Product::with('category')
   ->when($request->search,fn($q,$s)=>$q->where(fn($w)=>$w->where('name','like',"%{$s}%")->orWhere('sku','like',"%{$s}%")))
   ->when($request->category_id,fn($q,$id)=>$q->where('category_id',$id))
   ->orderBy($sort,$direction)->paginate(10)->withQueryString();
  $categories=Category::orderBy('name')->get();
  return view('products.index',compact('products','categories','sort','direction'));
 }
 public function create(){ $categories=Category::orderBy('name')->get(); return view('products.form',compact('categories')); }
 public function store(Request $request){
  $data=$request->validate(['category_id'=>'required|exists:categories,id','sku'=>'required|max:50|unique:products,sku','name'=>'required|max:150','price'=>'required|numeric|min:0','minimum_stock'=>'required|integer|min:0']);
  $data['stock']=0; Product::create($data);
  return redirect()->route('products.index')->with('success','Produk berhasil ditambahkan.');
 }
 public function edit(Product $product){$categories=Category::orderBy('name')->get();return view('products.form',compact('product','categories'));}
 public function update(Request $request,Product $product){
  $data=$request->validate(['category_id'=>'required|exists:categories,id','sku'=>['required','max:50',Rule::unique('products','sku')->ignore($product->id)],'name'=>'required|max:150','price'=>'required|numeric|min:0','minimum_stock'=>'required|integer|min:0']);
  $product->update($data);return redirect()->route('products.index')->with('success','Produk berhasil diperbarui.');
 }
 public function destroy(Product $product){
  if($product->stockMovements()->exists())return back()->withErrors(['product'=>'Produk dengan histori transaksi tidak dapat dihapus.']);
  $product->delete();return redirect()->route('products.index')->with('success','Produk berhasil dihapus.');
 }
}
