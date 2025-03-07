<?php

namespace App\Helper;

class ApiNamespace
{
    public $basePath = "\\App\\Http\\Controllers\\API\\";
    public $version;
    /**
     * Create a new class instance.
     */
    public function __construct($version = null)
    {
        $this->version = $version;
    }

    public function getFullPath() {
        return $this->version ? "{{$this->basePath}}\\{$this->version}" :$this->basePath;
    }

    public function getClass(string $class) {
        return ($this->getFullPath() . $class)::class;
    }

}
