<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::orderBy('order', 'asc')->get();
        return view('back.admin.team.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.team.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'position_az' => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_ru' => 'nullable|string|max:255',
            'biography_az' => 'nullable|string',
            'biography_en' => 'nullable|string',
            'biography_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'social_accounts.*.platform' => 'nullable|string',
            'social_accounts.*.url' => 'nullable|string|url',
            'social_accounts.*.icon' => 'nullable|string',
            'social_accounts.*.icon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // Son sıra numarasını belirle
        $maxOrder = Team::max('order') ?? 0;
        $data['order'] = $maxOrder + 1;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/team'), $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }
        
        // Sosyal medya hesaplarını düzenle
        if ($request->has('social_accounts')) {
            $socialAccounts = [];
            foreach ($request->social_accounts as $index => $account) {
                if (!empty($account['platform']) && !empty($account['url'])) {
                    $socialAccount = [
                        'platform' => $account['platform'],
                        'url' => $account['url'],
                        'icon' => $account['icon'] ?? '',
                    ];
                    
                    // İkon resmi işleme
                    if (isset($account['icon_file']) && $account['icon_file']) {
                        // Dizin yoksa oluştur
                        if (!file_exists(public_path('uploads/team/icons'))) {
                            mkdir(public_path('uploads/team/icons'), 0755, true);
                        }
                        
                        try {
                            $iconName = 'social-icon-' . time() . '-' . $index . '-' . uniqid() . '.' . $account['icon_file']->extension();
                            $account['icon_file']->move(public_path('uploads/team/icons'), $iconName);
                            $socialAccount['icon_image'] = 'uploads/team/icons/' . $iconName;
                            
                            // Başarıyla yüklendiğine dair debug bilgisi
                            \Log::info('Sosyal medya ikonu yüklendi: ' . $socialAccount['icon_image']);
                        } catch (\Exception $e) {
                            \Log::error('Sosyal medya ikonu yüklenirken hata: ' . $e->getMessage());
                        }
                    }
                    
                    $socialAccounts[] = $socialAccount;
                }
            }
            $data['social_accounts'] = $socialAccounts;
        }

        Team::create($data);

        return redirect()->route('back.pages.team.index')->with('success', 'Komanda üzvü uğurla əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('back.pages.team.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        return view('back.admin.team.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);
        
        $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'position_az' => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_ru' => 'nullable|string|max:255',
            'biography_az' => 'nullable|string',
            'biography_en' => 'nullable|string',
            'biography_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9999',
            'social_accounts.*.platform' => 'nullable|string',
            'social_accounts.*.url' => 'nullable|string|url',
            'social_accounts.*.icon' => 'nullable|string',
            'social_accounts.*.icon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski görseli sil
            if ($team->image && File::exists(public_path($team->image))) {
                File::delete(public_path($team->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/team'), $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }
        
        // Sosyal medya hesaplarını düzenle
        if ($request->has('social_accounts')) {
            $socialAccounts = [];
            foreach ($request->social_accounts as $index => $account) {
                if (!empty($account['platform']) && !empty($account['url'])) {
                    $socialAccount = [
                        'platform' => $account['platform'],
                        'url' => $account['url'],
                        'icon' => $account['icon'] ?? '',
                    ];
                    
                    // İkon resmi işleme
                    if (isset($account['icon_file']) && $account['icon_file']) {
                        // Dizin yoksa oluştur
                        if (!file_exists(public_path('uploads/team/icons'))) {
                            mkdir(public_path('uploads/team/icons'), 0755, true);
                        }
                        
                        try {
                            $iconName = 'social-icon-' . time() . '-' . $index . '-' . uniqid() . '.' . $account['icon_file']->extension();
                            $account['icon_file']->move(public_path('uploads/team/icons'), $iconName);
                            $socialAccount['icon_image'] = 'uploads/team/icons/' . $iconName;
                            
                            // Başarıyla yüklendiğine dair debug bilgisi
                            \Log::info('Sosyal medya ikonu yüklendi: ' . $socialAccount['icon_image']);
                        } catch (\Exception $e) {
                            \Log::error('Sosyal medya ikonu yüklenirken hata: ' . $e->getMessage());
                        }
                    } elseif (isset($account['icon_image'])) {
                        $socialAccount['icon_image'] = $account['icon_image'];
                    }
                    
                    $socialAccounts[] = $socialAccount;
                }
            }
            $data['social_accounts'] = $socialAccounts;
        }

        $team->update($data);

        return redirect()->route('back.pages.team.index')->with('success', 'Komanda üzvü uğurla yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);
        
        // Görseli sil
        if ($team->image && File::exists(public_path($team->image))) {
            File::delete(public_path($team->image));
        }
        
        $team->delete();

        return redirect()->route('back.pages.team.index')->with('success', 'Komanda üzvü uğurla silindi.');
    }
    
    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        $team = Team::findOrFail($id);
        $team->status = !$team->status;
        $team->save();

        return response()->json(['success' => true]);
    }
    
    /**
     * Update order of items
     */
    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:teams,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        foreach ($request->items as $item) {
            Team::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
} 