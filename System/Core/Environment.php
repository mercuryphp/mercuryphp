<?php

namespace System\Core;

final class Environment {
    
    private $rootDirectory;

    /**
     * Initializes an instance of Environment with the applications root directory.
     * The Environment class is a core class and should not be instantiated in
     * user code.
     */
    public function __construct($rootDirectory){
        $this->rootDirectory = $rootDirectory;
    }
    
    /**
     * Gets the applications root directory.
     */
    public function getRootDirectory() : string{
        return $this->rootDirectory;
    }
    
    /**
     * Gets the interface used between the webserver and PHP.
     */
    public function getSAPI() : string{
        return php_sapi_name();
    }
    
    /**
     * Gets an array of all included files.
     */
    public function getIncludedFiles() : array{
        return get_included_files();
    }
}
