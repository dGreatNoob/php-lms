<?php
/**
 * Security Helper Class
 * Provides common security functions for input validation, CSRF protection, etc.
 */

class Security
{
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitize input data
     */
    public static function sanitizeInput(string $input, int $maxLength = 255): string
    {
        $sanitized = trim(strip_tags($input));
        return substr($sanitized, 0, $maxLength);
    }

    /**
     * Validate email address
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate password strength
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        return $errors;
    }

    /**
     * Generate secure random password
     */
    public static function generateRandomPassword(int $length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * Rate limiting check
     */
    public static function checkRateLimit(string $key, int $maxAttempts = 5, int $timeWindow = 900): bool
    {
        $attempts = $_SESSION["rate_limit_$key"] ?? [];
        $now = time();
        
        // Remove old attempts outside time window
        $attempts = array_filter($attempts, function($timestamp) use ($now, $timeWindow) {
            return ($now - $timestamp) < $timeWindow;
        });
        
        // Check if max attempts exceeded
        if (count($attempts) >= $maxAttempts) {
            return false;
        }
        
        // Add current attempt
        $attempts[] = $now;
        $_SESSION["rate_limit_$key"] = $attempts;
        
        return true;
    }

    /**
     * Validate file upload
     */
    public static function validateFileUpload(array $file, array $allowedTypes = [], int $maxSize = 10485760): array
    {
        $errors = [];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = 'File is too large';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = 'File upload was incomplete';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = 'No file was uploaded';
                    break;
                default:
                    $errors[] = 'File upload failed';
            }
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File is too large (max: ' . self::formatBytes($maxSize) . ')';
        }
        
        // Check file type
        if (!empty($allowedTypes)) {
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mimeType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileExtension, $allowedTypes)) {
                $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
            }
            
            // Additional MIME type validation for security
            $allowedMimeTypes = [
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ];
            
            if (isset($allowedMimeTypes[$fileExtension]) && $mimeType !== $allowedMimeTypes[$fileExtension]) {
                $errors[] = 'File content does not match file extension';
            }
        }
        
        return $errors;
    }

    /**
     * Format bytes to human readable format
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * Generate secure filename
     */
    public static function generateSecureFilename(string $originalName): string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        
        return "upload_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent(string $event, array $details = []): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'user_id' => $_SESSION['user_id'] ?? null,
            'event' => $event,
            'details' => $details
        ];
        
        $logEntry = json_encode($logData) . "\n";
        $logFile = __DIR__ . '/../../storage/logs/security.log';
        
        // Create directory if it doesn't exist
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Check if request is HTTPS
     */
    public static function isHTTPS(): bool
    {
        return (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            $_SERVER['SERVER_PORT'] == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        );
    }

    /**
     * Force HTTPS redirect
     */
    public static function forceHTTPS(): void
    {
        if (!self::isHTTPS() && env('FORCE_HTTPS', false)) {
            $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $redirectURL", true, 301);
            exit();
        }
    }

    /**
     * Set secure headers
     */
    public static function setSecureHeaders(): void
    {
        // Prevent XSS attacks
        header('X-XSS-Protection: 1; mode=block');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Prevent clickjacking
        header('X-Frame-Options: DENY');
        
        // Enable HSTS in production
        if (env('APP_ENV') === 'production' && self::isHTTPS()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        
        // Content Security Policy (basic)
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
        
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}