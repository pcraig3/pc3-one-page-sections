<?php

/**
 * My blatantly ripped-off Autoloader.  Means that I don't have to manually include all of my classes
 * in `One_Page_Sections->load_dependencies()`
 * Heavily modelled on one used by Mike Toppa in his Shashin plugin
 *
 * @see: https://github.com/toppa/Shashin/blob/6252018a78085f3408cc594c1e01d78078ab41a4/lib/ShashinAutoLoader.php
 *
 * @since      0.9.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 */
class PC3AutoLoader {
    private $relativePath;
    private $className;
    private $fullPath;

    public function __construct($relativePath = null) {
        $this->relativePath = $relativePath;
        spl_autoload_register(array($this, 'loader'));
    }

    public function loader($className) {
        $this->setClassName($className);
        $this->setFullPath();
        $this->includeClass();
        return true;
    }

    public function setClassName($className) {
        $this->className = $className;
    }

    public function setFullPath() {
        $basePath = WP_PLUGIN_DIR . $this->relativePath;
        $classPath = str_replace('_', '/', $this->className) . '.php';

        // shashin has lower-case directory names
        $final_dir_separator = strrpos($classPath, '/');
        if ($final_dir_separator !== false) {

            //$classpath = 'Admin/Fields/PC3PageSelectPageField.php'
            //$dirs = 'Admin/Fields/'
            $dirs = substr($classPath, 0, $final_dir_separator + 1);
            //$filename = 'PC3PageSelectPageField.php'
            $filename = substr($classPath, $final_dir_separator + 1);

            $classPath = strtolower($dirs) . $filename;
        }

        $this->fullPath = $basePath . '/' . $classPath;
        return true;
    }

    public function includeClass() {
        if (class_exists($this->className, false)) {
            return true;
        }

        elseif (file_exists($this->fullPath)) {
            return @include($this->fullPath);
        }

        return false;
    }

    public function getFullPath() {
        return $this->fullPath;
    }

}
