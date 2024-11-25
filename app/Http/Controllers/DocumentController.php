<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $file = $request->file('document_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Store file in storage/app/public/documents
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'document_type' => $request->document_type,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_extension' => $file->getClientOriginalExtension(),
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        // Check if user has permission to download this document
        if ($document->user_id !== auth()->user()->id) {
            abort(403);
        }

        return Storage::download('public/' . $document->file_path, $document->file_name);
    }

    public function destroy(Document $document)
    {
        // Check if user has permission to delete this document
        if ($document->user_id !== auth()->user()->id) {
            abort(403);
        }

        // Delete file from storage
        Storage::delete('public/' . $document->file_path);
        
        // Delete record from database
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}