<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    /**
     * İletişim mesajlarının listesini gösterir
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        
        return view('back.admin.contactmessage.index', compact('messages', 'unreadCount'));
    }
    
    /**
     * Seçili mesajı gösterir
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Eğer okunmamışsa, okundu olarak işaretle
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }
        
        return view('back.admin.contactmessage.show', compact('message'));
    }
    
    /**
     * Mesajı sil
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('back.pages.contactmessage.index')
            ->with('success', 'Mesaj başarıyla silindi.');
    }
    
    /**
     * Toplu silme işlemi
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        ContactMessage::whereIn('id', explode(',', $ids))->delete();
        
        return response()->json(['success' => 'Seçili mesajlar başarıyla silindi.']);
    }
    
    /**
     * Mesajı okundu/okunmadı olarak işaretle
     */
    public function toggleReadStatus($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->is_read = !$message->is_read;
        $message->save();
        
        return redirect()->back()
            ->with('success', 'Mesaj durumu başarıyla güncellendi.');
    }
}
