<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Halaman admin: daftar barang temuan
    public function index()
    {
        $items = Item::with('category')->latest()->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    // Form input barang temuan (admin)
    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    // Simpan barang temuan
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'found_location' => 'required|string',
            'found_at'       => 'required|date',
            'stored_location'=> 'nullable|string',
            'contact'        => 'nullable|string',
            'main_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('items', 'public');
            $this->syncPublicStorageCopy($data['main_image']);
        }

        $data['created_by'] = auth()->id();

        $item = Item::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('items', 'public');
                $this->syncPublicStorageCopy($path);
                ItemImage::create([
                    'item_id' => $item->id,
                    'path'    => $path,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil disimpan.');
    }

    // Detail barang (untuk publik/user)
    public function show(Item $item)
    {
        $item->load('category', 'images', 'claims');
        return view('items.show', compact('item'));
    }

    // Form edit barang temuan (admin)
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    // Update barang temuan
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'found_location' => 'required|string',
            'found_at'       => 'required|date',
            'stored_location'=> 'nullable|string',
            'contact'        => 'nullable|string',
            'main_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('items', 'public');
            $this->syncPublicStorageCopy($data['main_image']);
        }

        $item->update($data);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    // Hapus barang temuan
    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }

    private function syncPublicStorageCopy(string $path): void
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return;
        }

        $source = $disk->path($path);
        $destination = public_path('storage/' . $path);
        $destinationDir = dirname($destination);

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (! file_exists($destination)) {
            copy($source, $destination);
        }
    }
}
