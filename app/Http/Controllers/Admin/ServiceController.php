<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::orderBy('order')->get();
        return view('back.admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::where('status', 1)->orderBy('order')->get();
        return view('back.admin.services.create', compact('categories'));
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
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'slug' => 'nullable|string|unique:services,slug',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug yaratma
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_az']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Görsel yükleme - Resim
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/services'), $imageName);
            $data['image'] = 'uploads/services/' . $imageName;
        }
        
        // Görsel yükleme - İkon
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = time() . '_icon_' . Str::random(10) . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/services'), $iconName);
            $data['icon'] = 'uploads/services/' . $iconName;
        }

        // Sıralama için son item'in değerini al
        $lastService = Service::orderBy('order', 'desc')->first();
        $data['order'] = $lastService ? $lastService->order + 1 : 1;

        Service::create($data);

        return redirect()->route('back.pages.services.index')->with('success', 'Xidmət uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.services.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::where('status', 1)->orderBy('order')->get();
        return view('back.admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'title_az' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_az' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'slug' => 'nullable|string|unique:services,slug,' . $id,
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Slug yaratma
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_az']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Görsel yükleme - Resim
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($service->image && File::exists(public_path($service->image))) {
                File::delete(public_path($service->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/services'), $imageName);
            $data['image'] = 'uploads/services/' . $imageName;
        }
        
        // Görsel yükleme - İkon
        if ($request->hasFile('icon')) {
            // Eski ikonu sil
            if ($service->icon && File::exists(public_path($service->icon))) {
                File::delete(public_path($service->icon));
            }
            
            $icon = $request->file('icon');
            $iconName = time() . '_icon_' . Str::random(10) . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/services'), $iconName);
            $data['icon'] = 'uploads/services/' . $iconName;
        }

        $service->update($data);

        return redirect()->route('back.pages.services.index')->with('success', 'Xidmət uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        
        // Görselleri sil
        if ($service->image && File::exists(public_path($service->image))) {
            File::delete(public_path($service->image));
        }
        
        if ($service->icon && File::exists(public_path($service->icon))) {
            File::delete(public_path($service->icon));
        }
        
        $service->delete();

        return redirect()->route('back.pages.services.index')->with('success', 'Xidmət uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $service = Service::findOrFail($id);
        $service->status = !$service->status;
        $service->save();

        return response()->json(['success' => true]);
    }
    
    /**
     * Reorder services
     */
    public function order(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:services,id',
        ]);
        
        foreach ($request->orders as $order => $id) {
            Service::where('id', $id)->update(['order' => $order]);
        }
        
        return response()->json(['success' => true]);
    }
}
