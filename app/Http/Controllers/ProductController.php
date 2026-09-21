<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of digital books (Public).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $selectedCategory = $request->query('category');

        $query = Product::query();

        if (! empty($search)) {
            $query->search($search);
        }

        if (! empty($selectedCategory) && $selectedCategory !== 'all') {
            $query->category($selectedCategory);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('products.index', compact('products', 'categories', 'search', 'selectedCategory'));
    }

    /**
     * Display the specified digital book (Public).
     */
    public function show(Product $product)
    {
        $relatedBooks = Product::where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->latest()
            ->take(3)
            ->get();

        if ($relatedBooks->isEmpty()) {
            $relatedBooks = Product::where('id', '!=', $product->id)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('products.show', compact('product', 'relatedBooks'));
    }

    /**
     * Preview or download the free sample excerpt of the book.
     */
    public function sample(Product $product)
    {
        if (empty($product->sample_file)) {
            return back()->with('error', 'Aucun extrait disponible pour cet ouvrage.');
        }

        if (filter_var($product->sample_file, FILTER_VALIDATE_URL)) {
            return redirect()->away($product->sample_file);
        }

        if (Storage::disk('public')->exists($product->sample_file)) {
            return Storage::disk('public')->response($product->sample_file);
        }

        if (Storage::disk('local')->exists($product->sample_file)) {
            return Storage::disk('local')->response($product->sample_file);
        }

        return back()->with('error', 'Fichier d\'extrait indisponible.');
    }

    /**
     * Display a listing of books in Admin panel.
     */
    public function adminIndex(Request $request)
    {
        $search = $request->query('search');
        $query = Product::query();

        if (! empty($search)) {
            $query->search($search);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new book (Admin).
     */
    public function create()
    {
        $existingCategories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('admin.products.create', compact('existingCategories'));
    }

    /**
     * Store a newly created digital book in storage (Admin).
     */
    public function store(Request $request)
    {
        $maxFileSizeKb = min($this->serverUploadLimitInKilobytes(), 102400);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'format' => 'required|string|max:50',
            'pages_count' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1800|max:2100',
            'isbn' => 'nullable|string|max:50',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:file,link',
            'file' => "required_if:product_type,file|file|max:{$maxFileSizeKb}",
            'drive_link' => 'required_if:product_type,link|nullable|url',
            'sample_type' => 'nullable|in:none,file,link',
            'sample_file_upload' => 'nullable|file|mimes:pdf,epub,doc,docx,mp3|max:30720',
            'sample_link' => 'nullable|url',
            'image_file' => 'required|image|max:4096',
            'chariow_product_id' => 'nullable|string|max:255',
            'testimonials_files.*' => 'nullable|image|max:2048',
        ]);

        // 1. Digital product delivery file/link
        $filePath = null;
        if ($validated['product_type'] === 'file' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('digital_products', 'local');
        } else {
            $filePath = $validated['drive_link'];
        }

        // 2. Sample excerpt
        $sampleFile = null;
        $sampleType = $request->input('sample_type', 'none');
        if ($sampleType === 'file' && $request->hasFile('sample_file_upload')) {
            $sampleFile = $request->file('sample_file_upload')->store('samples', 'public');
        } elseif ($sampleType === 'link' && ! empty($validated['sample_link'])) {
            $sampleFile = $validated['sample_link'];
        }

        // 3. Book Cover Image
        $imagePath = $request->file('image_file')->store('products', 'public');

        // 4. Testimonials
        $testimonials = [];
        if ($request->hasFile('testimonials_files')) {
            foreach ($request->file('testimonials_files') as $file) {
                $testimonials[] = $file->store('testimonials', 'public');
            }
        }

        Product::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category' => $validated['category'] ?: 'Général',
            'format' => $validated['format'] ?: 'PDF',
            'pages_count' => $validated['pages_count'] ?? null,
            'language' => $validated['language'] ?: 'Français',
            'publication_year' => $validated['publication_year'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'file_path' => $filePath,
            'sample_file' => $sampleFile,
            'image' => $imagePath,
            'chariow_product_id' => $validated['chariow_product_id'] ?? null,
            'testimonials' => $testimonials,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Livre ajouté avec succès à la boutique All_Books !');
    }

    /**
     * Show the form for editing the specified book (Admin).
     */
    public function edit(Product $product)
    {
        $existingCategories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('admin.products.edit', compact('product', 'existingCategories'));
    }

    /**
     * Update the specified digital book in storage (Admin).
     */
    public function update(Request $request, Product $product)
    {
        $maxFileSizeKb = min($this->serverUploadLimitInKilobytes(), 102400);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'format' => 'required|string|max:50',
            'pages_count' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1800|max:2100',
            'isbn' => 'nullable|string|max:50',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:file,link',
            'file' => "nullable|file|max:{$maxFileSizeKb}",
            'drive_link' => 'required_if:product_type,link|nullable|url',
            'sample_type' => 'nullable|in:none,file,link',
            'sample_file_upload' => 'nullable|file|mimes:pdf,epub,doc,docx,mp3|max:30720',
            'sample_link' => 'nullable|url',
            'image_file' => 'nullable|image|max:4096',
            'chariow_product_id' => 'nullable|string|max:255',
            'testimonials_files.*' => 'nullable|image|max:2048',
        ]);

        // Handle digital book delivery file/link
        if ($validated['product_type'] === 'file') {
            if ($request->hasFile('file')) {
                if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('local')->delete($product->file_path);
                }
                $product->file_path = $request->file('file')->store('digital_products', 'local');
            }
        } else {
            if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
                Storage::disk('local')->delete($product->file_path);
            }
            $product->file_path = $validated['drive_link'];
        }

        // Handle sample file
        $sampleType = $request->input('sample_type', 'none');
        if ($sampleType === 'file') {
            if ($request->hasFile('sample_file_upload')) {
                if ($product->sample_file && ! filter_var($product->sample_file, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($product->sample_file);
                }
                $product->sample_file = $request->file('sample_file_upload')->store('samples', 'public');
            }
        } elseif ($sampleType === 'link') {
            if ($product->sample_file && ! filter_var($product->sample_file, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->sample_file);
            }
            $product->sample_file = $validated['sample_link'];
        }

        // Handle cover image
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
        $product->author = $validated['author'];
        $product->category = $validated['category'] ?: 'Général';
        $product->format = $validated['format'] ?: 'PDF';
        $product->pages_count = $validated['pages_count'] ?? null;
        $product->language = $validated['language'] ?: 'Français';
        $product->publication_year = $validated['publication_year'] ?? null;
        $product->isbn = $validated['isbn'] ?? null;
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->chariow_product_id = $validated['chariow_product_id'] ?? null;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Livre mis à jour avec succès !');
    }

    /**
     * Remove the specified book from storage (Admin).
     */
    public function destroy(Product $product)
    {
        if ($product->file_path && ! filter_var($product->file_path, FILTER_VALIDATE_URL)) {
            Storage::disk('local')->delete($product->file_path);
        }

        if ($product->sample_file && ! filter_var($product->sample_file, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->sample_file);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Livre supprimé de la librairie avec succès !');
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
}
