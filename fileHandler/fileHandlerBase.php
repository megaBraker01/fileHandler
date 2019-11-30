<?php

namespace fileHandler;

require_once 'fileHandlerI.php';
use Exception;

/**
 * @package filesHandler
 * @author Rafael Perez <rafaelperez7461@gmail.com>
 * @copyright (c) 2017, Rafael Perez
 */

abstract class fileHandlerBase implements fileHandlerI
{
    protected $fileName;
    protected $accessMode;

    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     */
    public function __construct(string $fileName = null, string $accessMode = "a+"){
        if(isset($fileName)){
            $this->newFile($fileName, $accessMode);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getFileName(): string {
        $this->ifNotExistsThrowError((string) $this->fileName);
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
        $this->ifNotExistsThrowError($finalFileName);
        return filesize($finalFileName);
    }
        
    /**
     * 
     * @param string $fileName
     * @return array
     */
    public function getFileInfo(string $fileName = null): array {
        $finalFileName = $fileName ?? $this->getFileName();
        $this->ifNotExistsThrowError($finalFileName);
        return array_merge(stat($finalFileName), pathinfo($finalFileName));
    }

    /**
     * 
     * @param string $fileName
     * @return $this
     */
    private function setFileName(string $fileName) {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * 
     * @param string $accessMode
     * @return string
     */
    private function setAccessMode(string $accessMode) {
        switch (strtolower($accessMode)){
            case "r":
            case "r+": 
            case "w":
            case "w+":
            case "a":
            case "a+": 
            case "x":
            case "x+":
            case "c":
            case "c+":
            case "e":
                $this->accessMode = $accessMode;
                break;
            default : $this->accessMode = "r";
                break;
        }        
        return $accessMode;
    }

    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return $this
     */
    public function newFile(string $fileName, string $accessMode = "x+") {
        $file = $this->openFile($fileName, $accessMode);
        $this->setFileName($fileName);
        fclose($file);
        return $this;
    }
    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return type
     * @throws Exception
     */
    public function openFile(string $fileName, string $accessMode = "a+"){
        $mode = $this->setAccessMode($accessMode);
        if(!$fileName or !$file = fopen($fileName, $mode)){
            throw new \Exception("[ERROR] El archivo '$fileName' no se ha podido abrir o crear");
        }
        return $file;
    }

    /**
     * 
     * @param string $fileName
     * @param int $bytes
     * @param string $accessMode
     * @return string
     */
    public function read(string $fileName = null, int $bytes = 1, string $accessMode = "r"): string{
        $finalFileName = $fileName ?? $this->getFileName();
        $this->ifNotExistsThrowError($finalFileName);
        if(!$size = filesize($finalFileName)) { $fileSize = 1; }
        else { $fileSize = $bytes > 1 ? $bytes : $size; }
        $file = $this->openFile($finalFileName, $accessMode);
        $ret = fread($file, $fileSize);
        fclose($file);
        return $ret;
    }

    /**
     * 
     * @param string $newContent
     * @param int $writeMode los posibles valores son:
     * 1 escribe al final del archivo
     * 0 sobreescribe el contenido del archivo
     * -1 escribe al principio del archivo
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function write(string $newContent, int $writeMode = 1, string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        $this->ifNotExistsThrowError($finalFileName);
        switch ($writeMode){
            case 1 : $accessMode = "a";
                break;
            case 0 : $accessMode = "w";
                break;
            case -1 : 
                $accessMode = "w";
                $newContent .= $this->read($finalFileName);
                break;
        }
        $file = $this->openFile($finalFileName, $accessMode);
        if(!$ret = fwrite($file, $newContent."\n")){
            throw new \Exception("[ERROR] No se pudo escribir en el archivo '$finalFileName'");
        }
        fclose($file);
        return $ret;
    }
    
    /**
     * cambie el nombre de un archivo
     * se tiene que expecificar la extension del archivo en ambos parametros, ej; archivo.txt
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function rename(string $newName, string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        $this->ifNotExistsThrowError($finalFileName);
        if(!$newName || !$ret = rename($finalFileName, $newName)){
            throw new \Exception("[ERROR] No se pudo renombrar el archivo de '$finalFileName' a '$newName'");
        }
        $this->setFileName($newName);
        return $ret;
    }
    
    /**
     * 
     * @param string $fileToCopy
     * @param string $newFileName
     * @return type
     * @throws Exception
     */
    public function copy(string $fileToCopy = null, string $newFileName = null) {
        $fileToCopy = $fileToCopy ?? $this->getFileName();
        $this->ifNotExistsThrowError($fileToCopy);
        
        if(is_null($newFileName)){
            $i = 1;
            $originalNameFile = $this->getFileInfo($fileToCopy)['filename'];
            $extension = $this->getFileInfo($fileToCopy)['extension'];
            $newFileName = "$originalNameFile copy {$i}.{$extension}";
            while($this->exists($newFileName)){
                $i++;
                $newFileName = "$originalNameFile copy {$i}.{$extension}";
            }
        }
        
        if(!$ret = copy($fileToCopy, $newFileName)){
            throw new \Exception("[ERROR] No se pudo copiar el archivo '$fileToCopy'");
        }
        
        return $ret;
    }
    
    /**
     * 
     * @param string $fileName
     * @return type
     */
    public function exists(string $fileName = null): bool {
        $finalFileName = $fileName ?? $this->getFileName();
        return file_exists($finalFileName);
    }
    
    /**
     * 
     * @param string $fileName
     * @throws Exception
     */
    public function ifNotExistsThrowError(string $fileName = null) {
        if(!$fileName || !file_exists($fileName)){
            throw new \Exception("[ERROR] El archivo '$fileName' NO existe o el nombre NO es valido");
        }
    }

    /**
     * 
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function delete(string $fileName = null) {
        $finalFileName = $fileName ?? $this->getFileName();
        $this->ifNotExistsThrowError($finalFileName);
        if(!$ret = unlink($finalFileName)){
            throw new \Exception("[ERROR] No se pudo eliminar el archivo '$finalFileName'");
        }
        return $ret;
    }
    
}
