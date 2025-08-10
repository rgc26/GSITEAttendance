<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class SecureFileUpload
{
    /**
     * Allowed file extensions
     */
    protected $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'
    ];

    /**
     * Maximum file size in KB
     */
    protected $maxFileSize = 10240; // 10MB

    /**
     * Blocked file extensions (dangerous)
     */
    protected $blockedExtensions = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'pl', 'py', 'jsp', 'asp',
        'sh', 'cgi', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('file') || $request->hasFile('image') || $request->hasFile('document')) {
            $files = $request->allFiles();
            
            foreach ($files as $file) {
                if (is_array($file)) {
                    foreach ($file as $singleFile) {
                        if (!$this->validateFile($singleFile)) {
                            Log::warning('Blocked suspicious file upload attempt', [
                                'filename' => $singleFile->getClientOriginalName(),
                                'ip' => $request->ip(),
                                'user_agent' => $request->userAgent()
                            ]);
                            
                            return response()->json([
                                'error' => 'File type not allowed or file too large'
                            ], 400);
                        }
                    }
                } else {
                    if (!$this->validateFile($file)) {
                        Log::warning('Blocked suspicious file upload attempt', [
                            'filename' => $file->getClientOriginalName(),
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent()
                        ]);
                        
                        return response()->json([
                            'error' => 'File type not allowed or file too large'
                        ], 400);
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Validate individual file
     */
    protected function validateFile($file): bool
    {
        if (!$file->isValid()) {
            return false;
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $size = $file->getSize() / 1024; // Convert to KB

        // Check file size
        if ($size > $this->maxFileSize) {
            return false;
        }

        // Check for blocked extensions
        if (in_array($extension, $this->blockedExtensions)) {
            return false;
        }

        // Check for allowed extensions
        if (!in_array($extension, $this->allowedExtensions)) {
            return false;
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!$this->isAllowedMimeType($mimeType)) {
            return false;
        }

        // Check for double extensions (e.g., file.php.jpg)
        $filename = $file->getClientOriginalName();
        if ($this->hasDoubleExtension($filename)) {
            return false;
        }

        return true;
    }

    /**
     * Check if MIME type is allowed
     */
    protected function isAllowedMimeType($mimeType): bool
    {
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];

        return in_array($mimeType, $allowedMimeTypes);
    }

    /**
     * Check for double extensions
     */
    protected function hasDoubleExtension($filename): bool
    {
        $parts = explode('.', $filename);
        if (count($parts) > 2) {
            // Check if any part looks like a dangerous extension
            foreach ($parts as $part) {
                if (in_array(strtolower($part), $this->blockedExtensions)) {
                    return true;
                }
            }
        }
        return false;
    }
}
