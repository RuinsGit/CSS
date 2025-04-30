<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    private $destinationPath;
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
        'image/webp',
        'application/svg+xml',
        'application/svg',
    ];

    public function __construct()
    {
        $this->destinationPath = public_path('uploads/certificates');
    }

    protected function handleImageUpload($file, $prefix = '')
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // SVG dosyası kontrolü
        if ($file->getClientOriginalExtension() === 'svg') {
            $fileName = time() . '_' . $prefix . '_' . $originalFileName . '.svg';
            $file->move($this->destinationPath, $fileName);
            return 'uploads/certificates/' . $fileName;
        } else {
            // Diğer resim formatları için webp dönüşümü
            $webpFileName = time() . '_' . $prefix . '_' . $originalFileName . '.webp';

            if (!file_exists($this->destinationPath)) {
                mkdir($this->destinationPath, 0777, true);
            }

            $imageResource = imagecreatefromstring(file_get_contents($file));
            $webpPath = $this->destinationPath . '/' . $webpFileName;

            if ($imageResource) {
                imagewebp($imageResource, $webpPath, 80);
                imagedestroy($imageResource);
                return 'uploads/certificates/' . $webpFileName;
            }

            throw new \Exception('Resim işlenirken bir hata oluştu.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::orderBy('order', 'asc')->get();
        return view('back.pages.certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.pages.certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|mimes:jpeg,png,jpg,svg,webp',
        ]);
        
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['image' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }
                $data['image'] = $this->handleImageUpload($file);
            }
            
            Certificate::create($data);
            
            return redirect()->route('back.pages.certificates.index')->with('success', 'Sertifika başarıyla eklendi.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Resim işlenirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('back.pages.certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $certificate = Certificate::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
        ]);
        
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['image' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }
                
                if ($certificate->image) {
                    $oldImagePath = public_path($certificate->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $data['image'] = $this->handleImageUpload($file);
            }
            
            $certificate->update($data);
            
            return redirect()->route('back.pages.certificates.index')->with('success', 'Sertifika başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Resim işlenirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $certificate = Certificate::findOrFail($id);
        
        if ($certificate->image) {
            $imagePath = public_path($certificate->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $certificate->delete();
        
        return redirect()->route('back.pages.certificates.index')->with('success', 'Sertifika başarıyla silindi.');
    }
    
    public function toggleStatus($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = !$certificate->status;
        $certificate->save();
        
        return response()->json(['success' => true]);
    }
    
    public function order(Request $request)
    {
        $certificates = $request->get('certificates');
        
        foreach ($certificates as $certificate) {
            $c = Certificate::findOrFail($certificate['id']);
            $c->order = $certificate['order'];
            $c->save();
        }
        
        return response()->json(['success' => true]);
    }
}
