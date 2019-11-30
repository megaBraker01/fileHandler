<?php

namespace fileHandler;

/**
 * @package filesHandler
 * @author Rafael Perez <rafaelperez7461@gmail.com>
 * @copyright (c) 2017, Rafael Perez
 */

interface fileHandlerI 
{    
   public function getFileName(): string;
   
   public function getAccessMode(): string;
   
   /**
     * 
     * @param string $fileName
     * @return int
     */
    public function getSize(string $fileName = null): int;
    
    /**
     * 
     * @param string $fileName
     * @return array
     */
    public function getFileInfo(string $fileName = null): array;
    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return $this
     */
    public function newFile(string $fileName, string $accessMode = "x+");
    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return type
     * @throws Exception
     */
    public function openFile(string $fileName, string $accessMode = "a+");
    
    /**
     * 
     * @param string $fileName
     * @param int $bytes
     * @param string $accessMode
     * @return string
     */
    public function read(string $fileName = null, int $bytes = 1, string $accessMode = "r"): string;
    
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
    public function write(string $newContent, int $writeMode = 1, string $fileName = null);
    
    /**
     * cambie el nombre de un archivo
     * se tiene que expecificar la extension del archivo en ambos parametros, ej; archivo.txt
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function rename(string $newName, string $fileName = null);
    
    /**
     * 
     * @param string $fileToCopy
     * @param string $newFileName
     * @return type
     * @throws Exception
     */
    public function copy(string $fileToCopy = null, string $newFileName = null);
    
    /**
     * 
     * @param string $fileName
     * @return type
     */
    public function exists(string $fileName = null): bool;
    
    /**
     * 
     * @param string $fileName
     * @throws Exception
     */
    public function ifNotExistsThrowError(string $fileName = null);
    
    /**
     * 
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function delete(string $fileName = null);
    
}
