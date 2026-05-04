<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarcodeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $products = Product::query()
            ->with(['category', 'unit'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.admin.barcodes.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }
}
