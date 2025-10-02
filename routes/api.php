<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::patch('/employees/{employee}', [EmployeeController::class, 'inlineUpdate'])->name('employees.inlineUpdate');

Route::post('/s3/upload', function (Request $request) {
    // Check if a file is sent
    if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
        return response()->json([
            'error' => 'No file uploaded or file is invalid.',
        ], 400);
    }

    $file = $request->file('file');
    $originalName = $file->getClientOriginalName();
    $size = $file->getSize();
    $mimeType = $file->getMimeType();

    try {
        // Upload to S3
        $path = $file->store('test-uploads', 's3'); // folder: test-uploads

        if (!$path) {
            return response()->json(['error' => 'Failed to store the file.'], 500);
        }

        // Get the S3 URL
        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'original_name' => $originalName,
            'size' => $size,
            'mime_type' => $mimeType,
            'path' => $path,
            'url' => $url,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Upload failed.',
            'details' => $e->getMessage(),
        ], 500);
    }
});

