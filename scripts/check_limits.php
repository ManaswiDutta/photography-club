<?php
// scripts/check_limits.php
echo "--- PHP Upload Diagnostic ---\n";
echo "php.ini path: " . php_ini_loaded_file() . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . " seconds\n";
echo "-----------------------------\n";
?>
