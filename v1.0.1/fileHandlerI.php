<?php

namespace fileHandler;

/**
 * @package filesHandler
 * @author Rafael Perez <rafaelperez7461@gmail.com>
 * @copyright (c) 2017, Rafael Perez
 */

interface fileHandlerI {
    
    public function getFileName(): string;

    public function getAccessMode(): string;

    public function getSize(string $fileName = null): int;
    
    public function getFile();
    
    public function getFileInfo(string $fileName = null): array;
    
    public function close();
    
    /**
     * 
     * @param string $fileName
     * @param string $accessMode
     * @return $this
     * @throws Exception
     */
    public function newFile(string $fileName, string $accessMode = "a+");
    
    /**
     * 
     * @param string $fileName
     * @param int $bytes
     * @param string $accessMode
     * @return string
     * @throws Exception
     */
    public function read(string $fileName = null, int $bytes = 1, string $accessMode = "r"): string;

    /**
     * 
     * @param string $newContent
     * @param bool $overwrite
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function write(string $newContent, bool $overwrite = false, string $fileName = null);
    
    /**
     * cambie el nombre de un archivo
     * se tiene que expecificar la extension del archivo, ej; archivo.txt
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function rename(string $newName, string $fileName = null);
    
    /**
     * 
     * @param string $newName
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function copy(string $newName = null, string $fileName = null);
    
    /**
     * 
     * @param string $fileName
     * @return type
     */
    public function exists(string $fileName = null);

    /**
     * 
     * @param string $fileName
     * @return type
     * @throws Exception
     */
    public function delete(string $fileName = null);
}
