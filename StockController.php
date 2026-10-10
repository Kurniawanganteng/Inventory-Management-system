<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class StockController extends Controller {
 public function index(Request $request){
  $type=$request->query('type')==='OUT'?'OUT':'IN';
  $movements=StockMovement::with(['product','user'])->where('type',$type)->latest()->paginate(15)->withQueryString();
  $products=Product::orderBy('name')->get();
  return view('stock.index',compact('type','movements','products'));
 }
 public function store(Request $request){
  $data=$request->validate(['product_id'=>'required|exists:products,id','type'=>'required|in:IN,OUT','quantity'=>'required|integer|min:1','note'=>'nullable|string|max:255']);
  DB::transaction(function()use($data){
   $product=Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
   if($data['type']==='OUT' && $data['quantity']>$product->stock)throw \Illuminate\Validation\ValidationException::withMessages(['quantity'=>'Stok tidak cukup untuk barang keluar.']);
   $product->stock += $data['type']==='IN'?$data['quantity']:-$data['quantity'];$product->save();
   StockMovement::create(['product_id'=>$product->id,'user_id'=>auth()->id(),'type'=>$data['type'],'quantity'=>$data['quantity'],'note'=>$data['note']??null]);
  });
  return redirect()->route('stock.index',['type'=>$data['type']])->with('success','Transaksi stok berhasil disimpan.');
 }
}
