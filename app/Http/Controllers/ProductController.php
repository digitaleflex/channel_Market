<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products (Public).
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Display the specified product (Public).
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Display a listing of the products (Admin).
     */
    public function adminIndex()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product (Admin).
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage (Admin).
     */
    public function store(Request $request)
    {
        $maxFileSizeKb = min($this->serverUploadLimitInKilobytes(), 102400);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:file,link',
            'file' => "required_if:product_type,file|file|max:{$maxFileSizeKb}",
            'drive_link' => 'required_if:product_type,link|nullable|url',
            'image_file' => 'required|image|max:2048',
            'chariow_product_id' => 'nullable|string|max:255',
            'testimonials_files.*' => 'nullable|image|max:2048',
        ]);

        $filePath = null;
        if ($validated['product_type'] === 'file' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('digital_products', 'local');
        } else {
            $filePath = $validated['drive_link'];
        }

        $imagePath = $request->file('image_file')->store('products', 'public');

        $testimonials = [];
        if ($request->hasFile('testimonials_files')) {
            foreach ($request->file('testimonials_files') as $file) {
                $testimonials[] = $file->store('testimonials', 'public');
            }
        }

        Product::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'file_path' => $filePath,
            'image' => $imagePath,
            'chariow_product_id' => $validated['chariow_product_id'] ?? null,
            'testimonials' => $testimonials,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès !');
    }

    /**
     * Show the form for editing the specified product (Admin).
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage (Admin).
     */
    public function update(Request $request, Product $product)
    {
        $maxFileSizeKb = min($this->serverUploadLimitInKilobytes(), 102400);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:file,link',
            'file' => "nullable|file|max:{$maxFileSizeKb}",
            'drive_link' => 'required_if:product_type,link|nullable|url',
            'image_file' => 'nullable|image|max:2048',
            'chariow_product_id' => 'nullable|string|max:255',
            'testimonials_files.*' => 'nullable|image|max:2048',
        ]);

        // Handle digital product file/link
        if ($validated['product_type'] === 'file') {
            if ($request->hasFile('file')) {
                // Delete old file if it was a local file
                if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('local')->delete($product->file_path);
                }
                $product->file_path = $request->file('file')->store('digital_products', 'local');
            }
        } else {
            // It's a link
            if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
                Storage::disk('local')->delete($product->file_path);
            }
            $product->file_path = $validated['drive_link'];
        }

        // Handle image
        if ($request->hasFile('image_file')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image_file')->store('products', 'public');
        }

        // Handle testimonials
        if ($request->hasFile('testimonials_files')) {
            $testimonials = $product->testimonials ?? [];
            foreach ($request->file('testimonials_files') as $file) {
                $testimonials[] = $file->store('testimonials', 'public');
            }
            $product->testimonials = $testimonials;
        }

        $product->title = $validated['title'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->chariow_product_id = $validated['chariow_product_id'] ?? null;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Convert the current PHP upload limit into kilobytes.
     */
    private function serverUploadLimitInKilobytes(): int
    {
        $uploadMax = $this->phpSizeToKilobytes(ini_get('upload_max_filesize'));
        $postMax = $this->phpSizeToKilobytes(ini_get('post_max_size'));

        return min($uploadMax, $postMax) ?: 40960;
    }

    private function phpSizeToKilobytes(string $size): int
    {
        if (! preg_match('/^\s*(\d+)([KMG])?\s*$/i', trim($size), $matches)) {
            return 40960;
        }

        $value = (int) $matches[1];
        $unit = strtoupper($matches[2] ?? '');

        return match ($unit) {
            'G' => $value * 1024 * 1024,
            'M' => $value * 1024,
            'K' => $value,
            default => $value,
        };
    }

    /**
     * Remove the specified product from storage (Admin).
     */
    public function destroy(Product $product)
    {
        if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
            Storage::disk('local')->delete($product->file_path);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès !');
    }
}
