<?php

/**
 * @Author: Anwarul
 * @Date: 2026-01-20 10:36:41
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-02-07 14:52:25
 * @Description: Innova IT
 */

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentProfileRequest;
use App\Models\Lesson;
use App\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommonController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /**
     * Get the profile of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardInfo(Request $request)
    {
        try {
            $data = $this->commonService->getDashboardInfo($request);
            return ResponseHelper::success($data, 'Dashboard info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function mycourse(Request $request)
    {
        try {
            $data = $this->commonService->mycourse($request);
            return ResponseHelper::success($data, 'mycourse info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function courseDetails(Request $request, $slug)
    {
        try {
            $data = $this->commonService->courseDetails($slug);
            return ResponseHelper::success($data, 'mycourse info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

      public function enrollInfo(Request $request, $id)
    {
        try {
            $data = $this->commonService->enrollInfo($id);
            return ResponseHelper::success($data, 'enroll info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

   
   
    public function payment_history(Request $request)
    {
        try {
            $data = $this->commonService->payment_history($request);
            return ResponseHelper::success($data, 'My Book info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function order_history(Request $request)
    {
        try {
            $data = $this->commonService->order_history($request);
            return ResponseHelper::success($data, 'My Order info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function devices(Request $request)
    {
        try {
            $data = $this->commonService->devices($request);
            return ResponseHelper::success($data, 'My Device info return successfully');
        } catch (\Exception $e) {
            return $e;
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }



    public function removeDevice($id)
    {
        try {
            $this->commonService->removeDevice($id);
            return ResponseHelper::success([], 'Device removed successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function courseComments(Request $request)
    {
        try {

            $data = $request->validate([
                'course_id' => 'required',
                'module_id' => 'nullable',
                'lesson_id' => 'nullable',
                'question'  => 'required|string',
            ]);

            $comment = $this->commonService->courseComments($data);

            if (!$comment) {
                return ResponseHelper::error('Unauthorized', 401);
            }

            return ResponseHelper::success($comment, 'Comment added successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function courseCommentList(Request $request)
    {
        try {

            $courseId = $request->course_id;
            $lessonId = $request->lesson_id;

            $comments = $this->commonService->getCourseComments($courseId, $lessonId);

            return ResponseHelper::success($comments, 'Comments fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function courseQuiz($courseLug,$lessionId)
    {
        try {


            $comments = $this->commonService->getQuizQuestions($courseLug, $lessionId);

            return ResponseHelper::success($comments, 'Question fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function streamLessonPdf(Request $request)
    {
        // 1. Authentication
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Get lesson
        $lessonId = $request->input('lesson_id');
        $lesson = Lesson::find($lessonId);

        if (!$lesson || !$lesson->document) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // 3. Find the PDF file - FIXED
        $pdfPath = $this->findPdfFile($lesson->document);
        
        if (!$pdfPath) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }

        // 4. Generate HTML with EMBEDDED PDF
        return $this->generatePdfViewer($pdfPath, $lesson->title);
    }
    
    /**
     * Find PDF file in different locations
     */
    private function findPdfFile($documentPath)
    {
        // Remove any domain or path prefixes
        $fileName = basename($documentPath);
        
        // Check common storage locations
        $locations = [
            // Storage path
            storage_path('app/public/document/' . $fileName),
            storage_path('app/public/' . $documentPath),
            storage_path('app/' . $documentPath),
            
            // Public path
            public_path('storage/document/' . $fileName),
            public_path('storage/' . $documentPath),
            public_path($documentPath),
            
            // Direct path
            base_path('storage/app/public/document/' . $fileName),
            base_path('public/storage/document/' . $fileName),
        ];
        
        foreach ($locations as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Generate HTML Viewer with Embedded PDF
     */
    private function generatePdfViewer($pdfPath, $title)
    {
        // Convert PDF to base64
        $pdfContent = file_get_contents($pdfPath);
        $base64Pdf = base64_encode($pdfContent);
        
        // Simple HTML with embedded PDF
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Protected Document</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Security Banner */
        .security-banner {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .document-title {
            font-size: 22px;
            font-weight: 600;
            flex: 1;
            margin-right: 20px;
        }
        
        .security-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }
        
        /* PDF Viewer */
        .pdf-viewer {
            padding: 30px;
            background: #f8f9fa;
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .pdf-embed {
            width: 100%;
            max-width: 900px;
            height: 75vh;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            background: white;
        }
        
        /* Security Info */
        .security-info {
            background: #f1f3f4;
            padding: 20px;
            margin: 20px 30px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .security-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .security-features {
            list-style: none;
            padding: 0;
        }
        
        .security-features li {
            padding: 5px 0;
            color: #34495e;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-features li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: #95a5a6;
            padding: 15px 30px;
            text-align: center;
            font-size: 14px;
        }
        
        /* Loading */
        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            gap: 20px;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 15px;
            }
            
            .document-title {
                margin-right: 0;
                font-size: 18px;
            }
            
            .pdf-viewer {
                padding: 15px;
            }
            
            .pdf-embed {
                height: 60vh;
            }
        }
    </style>
</head>
<body>
    <!-- Security Banner -->
    <div class="security-banner">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        SECURE VIEWER - Downloading, Printing & Screenshots are Disabled
    </div>
    
    <!-- Main Container -->
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="document-title">{$title}</div>
            <div class="security-badge">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                PROTECTED DOCUMENT
            </div>
        </div>
        
        <!-- PDF Viewer -->
        <div class="pdf-viewer">
            <embed 
                class="pdf-embed"
                src="data:application/pdf;base64,{$base64Pdf}#toolbar=0&navpanes=0&scrollbar=0"
                type="application/pdf"
                id="pdfEmbed"
            />
        </div>
        
        <!-- Security Info -->
        <div class="security-info">
            <h3>🔒 Security Features Active:</h3>
            <ul class="security-features">
                <li>Right-click disabled</li>
                <li>Keyboard shortcuts blocked (Ctrl+S, Ctrl+P, etc.)</li>
                <li>Printing disabled</li>
                <li>Downloading prevented</li>
                <li>Screenshot protection</li>
                <li>Link copying not possible</li>
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>© " . date('Y') . " - Secure Document Viewer</div>
            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">
                This document is protected against unauthorized access and distribution
            </div>
        </div>
    </div>

    <script>
        // ========== SECURITY MEASURES ==========
        
        // 1. Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showSecurityAlert('Right-click is disabled');
            return false;
        });
        
        // 2. Disable keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Block Ctrl+S, Ctrl+P, Ctrl+Shift+S, etc.
            if (e.ctrlKey || e.metaKey) {
                if (['s', 'p', 'c', 'a'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                    showSecurityAlert('Keyboard shortcuts are disabled');
                    return false;
                }
            }
            
            // Block Print Screen
            if (e.key === 'PrintScreen') {
                e.preventDefault();
                showSecurityAlert('Screenshots are disabled');
                return false;
            }
            
            // Block F12 (Dev Tools)
            if (e.key === 'F12') {
                e.preventDefault();
                return false;
            }
        });
        
        // 3. Prevent text selection
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // 4. Prevent drag and drop
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // 5. PDF embed protection
        const pdfEmbed = document.getElementById('pdfEmbed');
        if (pdfEmbed) {
            // Try to add event listeners to embed
            try {
                const embedDoc = pdfEmbed.contentDocument || pdfEmbed.contentWindow.document;
                embedDoc.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    showSecurityAlert('Right-click is disabled');
                    return false;
                });
            } catch (error) {
                // Cross-origin restrictions
            }
        }
        
        // 6. Print protection
        window.addEventListener('beforeprint', function(e) {
            e.preventDefault();
            showSecurityAlert('Printing is disabled for protected documents');
            return false;
        });
        
        // ========== SECURITY ALERT FUNCTION ==========
        
        function showSecurityAlert(message) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.style.cssText = \`
                position: fixed;
                top: 50px;
                right: 20px;
                background: #ff4757;
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                z-index: 10000;
                font-weight: 500;
                animation: alertSlideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 400px;
            \`;
            
            alertDiv.innerHTML = \`
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                \${message}
            \`;
            
            // Add to body
            document.body.appendChild(alertDiv);
            
            // Add animation style
            if (!document.querySelector('#alertStyle')) {
                const style = document.createElement('style');
                style.id = 'alertStyle';
                style.textContent = \`
                    @keyframes alertSlideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                \`;
                document.head.appendChild(style);
            }
            
            // Remove after 3 seconds
            setTimeout(() => {
                alertDiv.style.animation = 'alertSlideIn 0.3s ease reverse';
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 300);
            }, 3000);
        }
        
        // ========== INITIAL SECURITY CHECK ==========
        
        // Show welcome message
        setTimeout(() => {
            showSecurityAlert('Secure document viewer loaded. Downloading disabled.');
        }, 1000);
        
        // Periodic security check
        setInterval(() => {
            // Detect if someone tries to inspect
            if (window.outerWidth - window.innerWidth > 200 || 
                window.outerHeight - window.innerHeight > 200) {
                showSecurityAlert('Security violation detected');
            }
        }, 1000);
        
        // Prevent leaving
        window.onbeforeunload = function() {
            return "Are you sure you want to leave? The document is protected.";
        };
        
        // Visibility change detection
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                showSecurityAlert('Document viewer hidden');
            }
        });
        
    </script>
</body>
</html>
HTML;

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    }
    
    /**
     * Alternative: Direct PDF response for testing
     */
    public function testPdf(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $lessonId = $request->input('lesson_id');
        $lesson = Lesson::find($lessonId);

        if (!$lesson || !$lesson->document) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $pdfPath = $this->findPdfFile($lesson->document);
        
        if (!$pdfPath || !file_exists($pdfPath)) {
            return response()->json([
                'message' => 'PDF not found',
                'document_path' => $lesson->document,
                'searched_path' => $pdfPath
            ], 404);
        }

        // Return the PDF directly
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"',
        ]);
    }
}

