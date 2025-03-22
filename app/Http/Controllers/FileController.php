<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function deleteFile(Request $request)
    {
        $filePath = $request->input('file_path');

        if (!$filePath) {
            return response()->json(['error' => 'File path is required'], 400);
        }

        try {
            $message = $this->firebaseService->deleteFile($filePath);
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
