<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Menu;
use App\Models\Category;

class MenuManagementController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Menu::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $menus = $query->latest()->paginate(10)->withQueryString();

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|max:3072',
            'is_featured' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $imagePath = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600';

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('menus', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $slug = Str::slug($validated['name']);
        // Ensure unique slug
        $count = Menu::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        Menu::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|max:3072',
            'is_featured' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $imagePath = $menu->image;
        if (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('menus', 'public');
            $imagePath = asset('storage/' . $path);
        }

        if ($menu->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $count = Menu::where('slug', 'like', "{$slug}%")->where('id', '!=', $menu->id)->count();
            if ($count > 0) {
                $slug = "{$slug}-" . ($count + 1);
            }
            $menu->slug = $slug;
        }

        $menu->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Data menu berhasil diperbarui!');
    }

    /**
     * Toggle availability status of a menu item.
     */
    public function toggleAvailability(Menu $menu)
    {
        $menu->update([
            'is_available' => !$menu->is_available,
        ]);

        $statusText = $menu->is_available ? 'Tersedia' : 'Habis / Non-Aktif';
        return back()->with('success', "Status menu '{$menu->name}' diubah menjadi: {$statusText}.");
    }

    /**
     * Handle bulk actions for menus.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:menus,id',
            'action' => 'required|in:delete,available,unavailable',
        ]);

        $count = count($validated['ids']);

        if ($validated['action'] === 'delete') {
            Menu::whereIn('id', $validated['ids'])->delete();
            return back()->with('success', "{$count} menu berhasil dihapus.");
        }

        $isAvailable = $validated['action'] === 'available';
        Menu::whereIn('id', $validated['ids'])->update([
            'is_available' => $isAvailable,
        ]);

        $statusLabel = $isAvailable ? 'Tersedia' : 'Habis / Non-Aktif';
        return back()->with('success', "Status {$count} menu berhasil diubah menjadi: {$statusLabel}.");
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
