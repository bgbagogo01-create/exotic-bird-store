<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display product catalog
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        
        $query = Product::with('category')->where('status', 'tersedia');

        // Apply Search Filter
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply Category Filter
        if ($request->has('category') && !empty($request->category)) {
            $categorySlug = $request->category;
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Display detailed product page
     */
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $adminPhone = Setting::get('shop_phone', '6281234567890');
        
        // Prefill WhatsApp text
        $messageText = "Halo Admin, saya tertarik untuk memesan barang berikut:\n\n";
        $messageText .= "Nama Barang: *{$product->name}*\n";
        $messageText .= "SKU: *{$product->sku}*\n";
        $messageText .= "Harga: *" . Setting::get('shop_currency', 'Rp') . " " . number_format($product->price, 0, ',', '.') . "*\n\n";
        $messageText .= "Apakah produk ini masih tersedia?";
        
        $waLink = "https://wa.me/{$adminPhone}?text=" . urlencode($messageText);

        // Get related products (same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'tersedia')
            ->limit(4)
            ->get();

        return view('catalog.show', compact('product', 'waLink', 'relatedProducts'));
    }
}
