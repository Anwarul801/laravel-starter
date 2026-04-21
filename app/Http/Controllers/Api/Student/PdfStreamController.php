<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class PdfStreamController extends Controller
{



    public function streamLessonPdf(Request $request)
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
        
        if (!$pdfPath) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }

        $pdfContent = file_get_contents($pdfPath);
        $base64Pdf = base64_encode($pdfContent);
        
        return $this->generateWorkingInnovaViewer($base64Pdf, $lesson->title);
    }
    
    private function findPdfFile($documentPath)
    {
        $fileName = basename($documentPath);
        
        $paths = [
            storage_path('app/public/document/' . $fileName),
            storage_path('app/public/' . $documentPath),
            public_path('storage/document/' . $fileName),
            public_path('storage/' . $documentPath),
            public_path($documentPath),
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    private function generateWorkingInnovaViewer($base64Pdf, $title)
    {
        $currentYear = date('Y');
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innova Document Viewer - {$title}</title>
    
    <style>
        /* Innova Design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            background: #000000;
            color: #ffffff;
            display: flex;
            flex-direction: column;
        }
        
        /* Innova Header */
        .tesla-header {
            background: rgba(0, 0, 0, 0.95);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #333333;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .tesla-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .tesla-logo-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #00D4FF, #0099FF);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .tesla-brand {
            font-size: 20px;
            font-weight: 300;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #00D4FF, #0099FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .document-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .document-title {
            font-size: 14px;
            color: #CCCCCC;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .security-status {
            background: rgba(255, 59, 48, 0.2);
            color: #FF3B30;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid rgba(255, 59, 48, 0.3);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 15px;
            min-height: 0;
            background: #111111;
            overflow: hidden;
        }
        
        /* PDF Container */
        .pdf-container {
            width: 100%;
            max-width: 1200px;
            flex: 1;
            background: #1A1A1A;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #333333;
            display: flex;
            flex-direction: column;
        }
        
        /* PDF Header */
        .pdf-header {
            padding: 15px 20px;
            background: #252525;
            border-bottom: 1px solid #333333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .pdf-title {
            font-size: 16px;
            font-weight: 500;
            color: #FFFFFF;
        }
        
        .pdf-controls {
            display: flex;
            gap: 10px;
        }
        
        .control-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* PDF Area - FULL SCROLLABLE */
        .pdf-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            background: #000000;
        }
        
        /* PDF Viewer Container */
        .pdf-viewer-container {
            width: 100%;
            min-height: 2000px;
            position: relative;
        }
        
        /* PDF Object */
        .pdf-object {
            width: 100%;
            height: 100%;
            min-height: 1000px;
            border: none;
        }
        
        /* Page Navigation */
        .page-navigation {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 50;
            opacity: 0.9;
            transition: opacity 0.3s;
        }
        
        .nav-btn {
            background: rgba(0, 212, 255, 0.9);
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 212, 255, 0.3);
        }
        
        .nav-btn:hover {
            background: rgba(0, 180, 255, 0.9);
        }
        
        /* Innova Footer */
        .tesla-footer {
            background: rgba(0, 0, 0, 0.95);
            padding: 12px 20px;
            border-top: 1px solid #333333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
        }
        
        .footer-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 12px;
            color: #888888;
        }
        
        .footer-features {
            display: flex;
            gap: 15px;
        }
        
        .feature-tag {
            background: rgba(0, 212, 255, 0.1);
            color: #00D4FF;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        /* Innova Alert */
        .tesla-alert {
            position: fixed;
            top: 70px;
            right: 20px;
            background: rgba(255, 59, 48, 0.9);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 13px;
            display: none;
            z-index: 1001;
            box-shadow: 0 4px 20px rgba(255, 59, 48, 0.3);
            animation: teslaSlideIn 0.3s ease;
            border-left: 4px solid #FF3B30;
            max-width: 350px;
        }
        
        @keyframes teslaSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Custom Scrollbar */
        .pdf-area::-webkit-scrollbar {
            width: 10px;
        }
        
        .pdf-area::-webkit-scrollbar-track {
            background: #1A1A1A;
        }
        
        .pdf-area::-webkit-scrollbar-thumb {
            background: #00D4FF;
            border-radius: 5px;
        }
        
        .pdf-area::-webkit-scrollbar-thumb:hover {
            background: #0099FF;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .pdf-container {
                border-radius: 8px;
            }
            
            .footer-features {
                display: none;
            }
            
            .page-navigation {
                bottom: 70px;
            }
            
            .nav-btn {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 0;
            }
            
            .pdf-container {
                border-radius: 0;
                border: none;
            }
            
            .page-navigation {
                bottom: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Innova Header -->
    <div class="tesla-header">
        <div class="tesla-logo">
            <div class="tesla-logo-icon">T</div>
            <div class="tesla-brand">INNOVA DOCUMENT VIEWER</div>
        </div>
        <div class="document-info">
            <div class="document-title">{$title}</div>
            <div class="security-status">🔒 SECURE MODE</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="pdf-container">
            <!-- PDF Header -->
            <div class="pdf-header">
                <div class="pdf-title">Document Preview</div>
                <div class="pdf-controls">
                    <button class="control-btn" onclick="scrollToTop()">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Top
                    </button>
                    <button class="control-btn" onclick="scrollToBottom()">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Bottom
                    </button>
                </div>
            </div>
            
            <!-- Scrollable PDF Area -->
            <div class="pdf-area" id="pdfArea">
                <!-- PDF Object - Direct PDF Viewer -->
                <div class="pdf-viewer-container">
                    <object 
                        class="pdf-object"
                        data="data:application/pdf;base64,{$base64Pdf}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                        type="application/pdf"
                        id="pdfObject"
                    >
                        <p style="color: white; padding: 50px; text-align: center;">
                            Your browser does not support PDF viewing. Please try a different browser.
                        </p>
                    </object>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Navigation -->
    <div class="page-navigation" id="pageNavigation">
        <button class="nav-btn" onclick="navigatePage('prev')">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Previous
        </button>
        <button class="nav-btn" onclick="navigatePage('next')">
            Next
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    
    <!-- Innova Footer -->
    <div class="tesla-footer">
        <div class="footer-info">
            <div>Innova Document Viewer </div>
            <div>© {$currentYear} Innova Security Systems</div>
            <div id="pageInfo">Viewing Document</div>
        </div>
       
    </div>
    
    <!-- Innova Alert -->
    <div class="tesla-alert" id="teslaAlert">
        <div id="alertMessage"></div>
    </div>

    <script>
        let currentPage = 1;
        
        window.addEventListener('load', function() {
            console.log('Innova PDF Viewer Initialized');
            
            setupSecurity();
            
            setupScrolling();
            
            setTimeout(() => {
                showInnovaAlert('Innova Document Viewer Active - Scroll to navigate', 3000);
            }, 1000);
        });
        
        
        function setupScrolling() {
            const pdfArea = document.getElementById('pdfArea');
            if (!pdfArea) return;
            
            pdfArea.style.scrollBehavior = 'smooth';
            
            pdfArea.addEventListener('scroll', function() {
                updatePageInfo();
            });
            
            pdfArea.addEventListener('wheel', function(e) {
                return true;
            }, { passive: true });
            
            pdfArea.addEventListener('touchmove', function(e) {
                return true;
            }, { passive: true });
            
            const pdfObject = document.getElementById('pdfObject');
            if (pdfObject) {
                pdfObject.style.pointerEvents = 'none';
            }
        }
        
        function scrollToTop() {
            const pdfArea = document.getElementById('pdfArea');
            if (pdfArea) {
                pdfArea.scrollTo({ top: 0, behavior: 'smooth' });
                showInnovaAlert('Scrolled to top', 1500);
            }
        }
        
        function scrollToBottom() {
            const pdfArea = document.getElementById('pdfArea');
            if (pdfArea) {
                const maxScroll = pdfArea.scrollHeight - pdfArea.clientHeight;
                pdfArea.scrollTo({ top: maxScroll, behavior: 'smooth' });
                showInnovaAlert('Scrolled to bottom', 1500);
            }
        }
        
        function navigatePage(direction) {
            const pdfArea = document.getElementById('pdfArea');
            if (!pdfArea) return;
            
            const viewportHeight = pdfArea.clientHeight;
            const currentPosition = pdfArea.scrollTop;
            let newPosition;
            
            if (direction === 'next') {
                newPosition = currentPosition + viewportHeight;
            } else {
                newPosition = currentPosition - viewportHeight;
            }
            
            const maxScroll = pdfArea.scrollHeight - viewportHeight;
            if (newPosition < 0) newPosition = 0;
            if (newPosition > maxScroll) newPosition = maxScroll;
            
            pdfArea.scrollTo({ top: newPosition, behavior: 'smooth' });
            
            showInnovaAlert(direction === 'next' ? 'Next section' : 'Previous section', 1500);
        }
        
        function updatePageInfo() {
            const pageInfo = document.getElementById('pageInfo');
            if (pageInfo) {
                const pdfArea = document.getElementById('pdfArea');
                if (pdfArea) {
                    const viewportHeight = pdfArea.clientHeight;
                    const currentScroll = pdfArea.scrollTop;
                    currentPage = Math.floor(currentScroll / viewportHeight) + 1;
                    pageInfo.textContent = 'Page: ' + currentPage;
                }
            }
        }
        
        
        function setupSecurity() {
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showInnovaAlert('Innova Security: Right-click disabled', 2000);
                return false;
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'PrintScreen' || e.code === 'PrintScreen') {
                    e.preventDefault();
                    e.stopPropagation();
                    showInnovaAlert('Innova Security: Screenshots are blocked', 2000);
                    return false;
                }
                
                if ((e.key === 's' || e.key === 'S' || e.key === '4') && 
                    (e.metaKey || e.ctrlKey) && e.shiftKey) {
                    e.preventDefault();
                    e.stopPropagation();
                    showInnovaAlert('Innova Security: Snipping tools blocked', 2000);
                    return false;
                }
                
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    e.stopPropagation();
                    showInnovaAlert('Innova Security: Printing disabled', 2000);
                    return false;
                }

                
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    e.stopPropagation();
                    showInnovaAlert('Innova Security: Saving disabled', 2000);
                    return false;
                }
                
                if (e.key === 'F12') {
                    e.preventDefault();
                    e.stopPropagation();
                    showInnovaAlert('Innova Security: Developer tools restricted', 2000);
                    return false;
                }
            });
            
            window.addEventListener('beforeprint', function(e) {
                e.preventDefault();
                showInnovaAlert('Innova Security: Printing not available', 2000);
                return false;
            });
            
            window.print = function() {
                showInnovaAlert('Innova Security: Print function disabled', 2000);
                return false;
            };
            
            document.addEventListener('selectstart', function(e) {
                e.preventDefault();
                return false;
            });
            
            document.addEventListener('copy', function(e) {
                e.preventDefault();
                showInnovaAlert('Innova Security: Copying disabled', 2000);
                return false;
            });
            
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
            
            document.addEventListener('drop', function(e) {
                e.preventDefault();
                showInnovaAlert('Innova Security: File drop disabled', 2000);
                return false;
            });
        }
        
        
        function showInnovaAlert(message, duration = 3000) {
            const alert = document.getElementById('teslaAlert');
            const messageEl = document.getElementById('alertMessage');
            
            if (alert && messageEl) {
                messageEl.textContent = message;
                alert.style.display = 'block';
                
                setTimeout(() => {
                    alert.style.animation = 'teslaSlideIn 0.3s ease reverse';
                    setTimeout(() => {
                        alert.style.display = 'none';
                        alert.style.animation = 'teslaSlideIn 0.3s ease';
                    }, 300);
                }, duration);
            }
        }
        
        window.onbeforeunload = function(e) {
            const message = "Innova Security: Are you sure you want to leave?";
            e.returnValue = message;
            return message;
        };


        
        
    </script>
</body>
</html>
HTML;

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}