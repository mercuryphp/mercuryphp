<?php

namespace System\Core;

class ExceptionHandler {
    
    protected $response;
    
    public function __construct($response){
        $this->response = $response;
    }
    
    public function write(\Exception $e){
        
        $codeSegments = file($e->getFile());
        $code = '';
        
        for($idx = $e->getLine()-2; $idx < $e->getLine()+1; $idx++){
            $code .= isset($codeSegments[$idx]) ? $codeSegments[$idx] : '';
        }

        $codeLine = isset($code[$e->getLine()-1]) ? $code[$e->getLine()-1] : '';
        $str = \System\Core\Str::set('<div style="font-family:Arial; font-size:12px;"><h1 style="font-size:25px; padding:6px 0px; color:orange; border-bottom:2px solid #EEE; font-weight:normal;">{exception}</h1>
            <p style="color: #990000;font-style: italic;font-size: 15px;">{message}</p>
            <div>{file} on line <strong>{lineNumber}</strong></div>
            <h4>Code</h4>
            <pre style="background-color: #E9EFF8; font-family:Arial; font-size:12px; padding:10px 0px; line-height:20px;">{code}</pre>
            <h4>Trace</h4>
            <div>{trace}</div></div>')->tokens([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'code' => $code,
            'lineNumber' => $e->getLine(),
            'trace' => str_replace(PHP_EOL, "<br/>", $e->getTraceAsString())
        ]);
        
        $this->response->write((string)$str)->setStatusCode(500);
    }
}