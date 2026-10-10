<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  User::firstOrCreate(['email'=>'admin@example.test'],['name'=>'Administrator','password'=>Hash::make('password')]);
  User::firstOrCreate(['email'=>'staff@example.test'],['name'=>'Staff Inventory','password'=>Hash::make('password')]);
  $electronics=Category::firstOrCreate(['name'=>'Elektronik'],['description'=>'Perangkat elektronik']);
  $office=Category::firstOrCreate(['name'=>'Peralatan Kantor'],['description'=>'Perlengkapan kantor']);
  $s1=Supplier::firstOrCreate(['name'=>'Sumber Jaya'],['phone'=>'081234567890','email'=>'sales@example.test','address'=>'Medan']);
  $s2=Supplier::firstOrCreate(['name'=>'Mitra Niaga'],['phone'=>'081298765432','email'=>'mitra@example.test','address'=>'Binjai']);
  $p1=Product::firstOrCreate(['sku'=>'EL-001'],['category_id'=>$electronics->id,'name'=>'Keyboard Mechanical','price'=>350000,'stock'=>28,'minimum_stock'=>5]);
  $p2=Product::firstOrCreate(['sku'=>'KT-001'],['category_id'=>$office->id,'name'=>'Kertas A4','price'=>58000,'stock'=>42,'minimum_stock'=>10]);
  $p1->suppliers()->syncWithoutDetaching([$s1->id=>['supplier_price'=>290000]]);
  $p2->suppliers()->syncWithoutDetaching([$s2->id=>['supplier_price'=>47000]]);
 }
}
