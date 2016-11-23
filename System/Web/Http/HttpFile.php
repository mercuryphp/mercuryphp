<?php

namespace System\Web\Http;

final class HttpFile {
    
    private $id;
    private $fileName;
    private $type;
    private $tmpName;
    private $error;
    private $size;

    public function __construct(string $id, string $fileName, string $type, string $tmpName, int $error, int $size){
        $this->id = $id;
        $this->fileName = $fileName;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
    }
    
    public function getId() : string {
        return $this->id;
    }
    
    public function getFileName() : string {
        return $this->fileName;
    }
    
    public function getType() : string {
        return $this->type;
    }
    
    public function getTmpName() : string {
        return $this->tmpName;
    }
    
    public function gerError() : int {
        return $this->id;
    }
    
    public function getSize() : int {
        return $this->size;
    }
    
    /**
     * Gets the file extension of the file being uploaded.
     */
    public function getExtension() : string {
        return substr($this->fileName, strripos($this->fileName, '.')+1);
    }
    
    public function save($destination) : bool {
        try{
            return move_uploaded_file($this->tmpName, $destination);
        }catch(\ErrorException $e){
            throw new HttpFileException('Unable to move file.', $e->getCode(), $e);
        }
    }
}