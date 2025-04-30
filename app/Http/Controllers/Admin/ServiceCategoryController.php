<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ServiceCategory::orderBy('order')->get();
        return view('back.admin.service-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.service-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'slug' => 'nullable|string|unique:service_categories,slug',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug yaratma
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_az']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/service-categories'), $imageName);
            $data['image'] = 'uploads/service-categories/' . $imageName;
        }

        // Sıralama için son kategori değerini al
        $lastCategory = ServiceCategory::orderBy('order', 'desc')->first();
        $data['order'] = $lastCategory ? $lastCategory->order + 1 : 1;

        ServiceCategory::create($data);

        return redirect()->route('back.pages.service-categories.index')->with('success', 'Kateqoriya uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.service-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        return view('back.admin.service-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'slug' => 'nullable|string|unique:service_categories,slug,' . $id,
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug yaratma
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_az']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/service-categories'), $imageName);
            $data['image'] = 'uploads/service-categories/' . $imageName;
        }

        $category->update($data);

        return redirect()->route('back.pages.service-categories.index')->with('success', 'Kateqoriya uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        
        // Görseli sil
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        
        // Bu kategoriye bağlı servisleri serbest bırak
        $category->services()->update(['service_category_id' => null]);
        
        $category->delete();

        return redirect()->route('back.pages.service-categories.index')->with('success', 'Kateqoriya uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        return response()->json(['success' => true]);
    }
    
    /**
     * Reorder categories
     */
    public function order(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:service_categories,id',
        ]);
        
        foreach ($request->orders as $order => $id) {
            ServiceCategory::where('id', $id)->update(['order' => $order]);
        }
        
        return response()->json(['success' => true]);
    }
}
