<?php
namespace SS;

class Log 
{   
    const DELIMITER = "\n-----------------------------------\n";
    
    private static $_filePath;
    
    public static function setFilePath($filePath)
    {
        self::$_filePath = $filePath;
    }
    
    public static function log($text, $filePath = null)
    {
        if (null == $filePath) {
            $filePath = self::$_filePath;
        }
        
        $fileHandle = fopen($filePath, 'a+');
        fwrite($fileHandle, "\n[" . date('Y-m-d H:i:s') . "]\n");
        fwrite($fileHandle, $text);
        fwrite($fileHandle, self::DELIMITER);
        fclose($fileHandle);
    }
}