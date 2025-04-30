<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        
        $request->validate([
            'name_az' => 'required',
            'position_az' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social_accounts.*.platform' => 'required',
            'social_accounts.*.url' => 'required|url',
            'social_accounts.*.icon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Resim işleme
        if ($request->hasFile('image')) {
            if ($team->image && file_exists(public_path($team->image))) {
                unlink(public_path($team->image));
            }
            
            $imageName = time() . '-' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/team'), $imageName);
            $team->image = 'uploads/team/' . $imageName;
        }
        
        // Sosyal hesaplar
        $socialAccounts = [];
        if ($request->has('social_accounts')) {
            foreach ($request->social_accounts as $index => $account) {
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
        
        // Diğer bilgileri kaydet
        $team->name_az = $request->name_az;
        $team->name_en = $request->name_en;
        $team->name_ru = $request->name_ru;
        $team->position_az = $request->position_az;
        $team->position_en = $request->position_en;
        $team->position_ru = $request->position_ru;
        $team->biography_az = $request->biography_az;
        $team->biography_en = $request->biography_en;
        $team->biography_ru = $request->biography_ru;
        $team->status = $request->has('status') ? 1 : 0;
        $team->social_accounts = $socialAccounts;
        $team->save();
        
        return redirect()->route('back.pages.team.index')->with('success', 'Komanda üzvü uğurla yeniləndi!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_az' => 'required',
            'position_az' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social_accounts.*.platform' => 'required',
            'social_accounts.*.url' => 'required|url',
            'social_accounts.*.icon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Resim işleme
        $imageName = time() . '-' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/team'), $imageName);
        
        // Sosyal hesaplar
        $socialAccounts = [];
        if ($request->has('social_accounts')) {
            foreach ($request->social_accounts as $index => $account) {
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
        
        // Yeni takım üyesi oluştur
        Team::create([
            'name_az' => $request->name_az,
            'name_en' => $request->name_en,
            'name_ru' => $request->name_ru,
            'position_az' => $request->position_az,
            'position_en' => $request->position_en,
            'position_ru' => $request->position_ru,
            'biography_az' => $request->biography_az,
            'biography_en' => $request->biography_en,
            'biography_ru' => $request->biography_ru,
            'image' => 'uploads/team/' . $imageName,
            'status' => $request->has('status') ? 1 : 0,
            'social_accounts' => $socialAccounts,
        ]);
        
        return redirect()->route('back.pages.team.index')->with('success', 'Komanda üzvü uğurla əlavə edildi!');
    }
} 