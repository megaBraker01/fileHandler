<?php

namespace fileHandler;

/**
 * @package filesHandler
 * @author Rafael Perez <rafaelperez7461@gmail.com>
 * @copyright (c) 2017, Rafael Perez
 */

class fileHandlerBase {
    protected $fileName;
    protected $accessMode;
    protected $file;

    public function __construct(string $fileName = null, $accessMode = "a+"){
        if(isset($fileName)){
            if(!$file = fopen($fileName, $accessMode)){
                throw new Exception("[ERROR] No se ha podido abrir o crear el archivo ".$fileName);
            }
            $this->setFileName($fileName)->setAccessMode($accessMode)->setFile($file);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getFileName(): string {
        return $this->fileName;
    }

    /**
     * 
     * @return string
     */
    public function getAccessMode(): string {
        return $this->accessMode;
    }

    /**
     * 
     * @param string $fileName
     * @return int
     */
    public function getSize(string $fileName = null): int {
        $finalFileName = $fileName ?? $this->getFileName();
        return filesize($finalFileName);
    }
    
    public function getFile() {
        return $this->file;
    }
    
    /**
     * 
     * @param string $fileName
     * @return array
     */
    public function getFileInfo(string $fileName = null): array {
        $finalFileName = $fileName ?? $this->getFileName();
        return array_merge(pathinfo($finalFileName), stat($finalFileName));
    }

    private function setFileName(string $fileName) {
        $this->fileName = $fileName;
        return $this;
    }

    private function setAccessMode(string $accessMode) {
        $this->accessMode = $accessMode;
        return $this;
    }

    private function setFile($file) {
        $this->file = $file;
        return $this;
    }
    
    public function close() {
        return fclose($this->getFile());
    }
    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return $this
     * @throws Exception
     */
    public function newFile(string $fileName, string $accessMode = "a+") {
        if(!$file = fopen($fileName, $accessMode)){
            throw new Exception("[ERROR] No se ha podido abrir o crear el archivo ".$fileName);
        }
        $this->setFileName($fileName)->setAccessMode($accessMode)->setFile($file);
        return $this;
    }
    
    /**
     * 
     * @param string $fileName
     * @param int $bytes
     * @param string $accessMode
     * @return string
     * @throws Exception
     */
    public function read(string $fileName = null, int $bytes = 1, string $accessMode = "r"): string{
        $finalFileName = $fileName ?? $this->getFileName();
        if(is_null($finalFileName)){
            throw new Exception("[ERROR] El nombre de archivo NO puede ser nulo ");
        }
        
        if(!$size = filesize($finalFileName)) { $fileSize = 1; }
        else { $fileSize = $bytes > 1 ? $bytes : $size; }
        
        $file = fopen($finalFileName, $accessMode);
        return fread($file, $fileSize);
    }

    /**
     * 
     * @param string $newContent
     * @param bool $overwrite
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function write(string $newContent, bool $overwrite = false, string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        $accessMode = $overwrite ? 'w' : 'a';
        $file = fopen($finalFileName, $accessMode);
        if(!$ret = fwrite($file, $newContent."\n")){
            throw new Exception("[ERROR] No se pudo escribir en el archivo ".$finalFileName);
        }
        return $ret;
    }
    
    /**
     * cambie el nombre de un archivo
     * se tiene que expecificar la extension del archivo, ej; archivo.txt
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function rename(string $newName, string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        if(!$ret = rename($finalFileName, $newName)){
            throw new Exception("[ERROR] No se pudo renombrar el archivo ".$finalFileName);
        }
        return $ret;
    }
    
    /**
     * 
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function copy(string $newName = null, string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        
        if(is_null($newName)){
            $i = 1;
            $newName = "copy {$i} {$finalFileName}";
            while ($this->exists($newName)){
                $i++;
                $newName = "copy {$i} {$finalFileName}";
            }
        }
        
        if(!$ret = copy($finalFileName, $newName)){
            throw new Exception("[ERROR] No se pudo copiar el archivo ".$finalFileName);
        }
        
        return $ret;
    }
    
    /**
     * 
     * @param string $fileName
     * @return type
     */
    public function exists(string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        return file_exists($finalFileName);
    }

    /**
     * 
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function delete(string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        if(!$ret = unlink($finalFileName)){
            throw new Exception("[ERROR] No se pudo eliminar el archivo ".$finalFileName);
        }
        return $ret;
    }
    
}
