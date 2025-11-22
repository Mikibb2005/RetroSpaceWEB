<?php
/**
 * Helper para subir archivos de imágenes y videos
 */

class UploadHelper {
    private static $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private static $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    private static $maxFileSize = 10485760; // 10MB
    private static $maxFiles = 3;
    
    /**
     * Procesa múltiples archivos subidos
     * @param array $files Array de $_FILES['nombre']
     * @param string $uploadDir Directorio donde guardar (diario, proyectos, foro)
     * @return array Array de rutas de archivos guardados o array vacío si hay error
     */
    public static function uploadMultiple($files, $uploadDir) {
        if (!isset($files['name']) || !is_array($files['name'])) {
            return [];
        }
        
        $uploadedFiles = [];
        $baseDir = __DIR__ . '/../../public/uploads/' . $uploadDir . '/';
        
        // Asegurar que el directorio existe
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        
        $fileCount = count($files['name']);
        $fileCount = min($fileCount, self::$maxFiles); // Máximo 3 archivos
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            
            // Validar tamaño
            if ($files['size'][$i] > self::$maxFileSize) {
                continue;
            }
            
            // Validar tipo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $files['tmp_name'][$i]);
            finfo_close($finfo);
            
            $isImage = in_array($mimeType, self::$allowedImageTypes);
            $isVideo = in_array($mimeType, self::$allowedVideoTypes);
            
            if (!$isImage && !$isVideo) {
                continue;
            }
            
            // Generar nombre único
            $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filepath = $baseDir . $filename;
            
            // Mover archivo
            if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                $uploadedFiles[] = '/uploads/' . $uploadDir . '/' . $filename;
            }
        }
        
        return $uploadedFiles;
    }
    
    /**
     * Elimina archivos físicos
     * @param array $files Array de rutas de archivos
     */
    public static function deleteFiles($files) {
        if (!is_array($files)) {
            $files = json_decode($files, true);
        }
        
        if (!$files) return;
        
        foreach ($files as $file) {
            $fullPath = __DIR__ . '/../../public' . $file;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
?>
