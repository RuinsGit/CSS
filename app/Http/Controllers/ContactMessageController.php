<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;

class ContactMessageController extends Controller
{
    /**
     * İletişim sayfasını göster
     */
    public function index()
    {
        return view('front.contactmessage');
    }
    
    /**
     * İletişim formunu işle
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
        ContactMessage::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.'
        ]);
    }
}
