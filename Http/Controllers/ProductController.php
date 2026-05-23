<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category');

        // Apply Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply Category Filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Apply Status Filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('name', 'asc')->paginate(10);
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'name.required' => 'Nama barang wajib diisi.',
            'name.unique' => 'Nama barang sudah terdaftar.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU sudah digunakan.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok awal wajib diisi.',
            'min_stock.required' => 'Stok minimum wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $status = $request->stock > 0 ? 'tersedia' : 'habis';

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => strtoupper($request->sku),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'status' => $status,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('toast_success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'name.required' => 'Nama barang wajib diisi.',
            'name.unique' => 'Nama barang sudah terdaftar.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU sudah digunakan.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'min_stock.required' => 'Stok minimum wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $status = $request->stock > 0 ? 'tersedia' : 'habis';

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => strtoupper($request->sku),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'status' => $status,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('toast_success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete image from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('toast_success', 'Barang berhasil dihapus dari inventaris.');
    }
}
