<?php

declare(strict_types=1);

namespace App\Utils;

use function copy;
use function dirname;
use function file_put_contents;
use function is_dir;
use function rename;
use function unlink;

final class Filesystem{

	private function __construct(){
		//NOOP
	}
	/**
	 * Wrapper around file_put_contents() which writes to a temporary file before overwriting the original. If the disk
	 * is full, writing to the temporary file will fail before the original file is modified, leaving it untouched.
	 *
	 * This is necessary because file_put_contents() destroys the data currently in the file if it fails to write the
	 * new contents.
	 *
	 * @param resource|null $context Context to pass to file_put_contents
	 *
	 * @throws \RuntimeException if the operation failed for any reason
	 */
	public static function safeFilePutContents(string $fileName, string $contents, int $flags = 0, $context = null) : void{
		$directory = dirname($fileName);
		if(!is_dir($directory)){
			throw new \RuntimeException("Target directory path does not exist or is not a directory");
		}
		if(is_dir($fileName)){
			throw new \RuntimeException("Target file path already exists and is not a file");
		}

		$counter = 0;
		do{
			//we don't care about overwriting any preexisting tmpfile but we can't write if a directory is already here
			$temporaryFileName = $fileName . ".$counter.tmp";
			$counter++;
		}while(is_dir($temporaryFileName));

		try{
            ($context !== null ?
                file_put_contents($temporaryFileName, $contents, $flags, $context) :
                file_put_contents($temporaryFileName, $contents, $flags));
		}catch(\ErrorException $filePutContentsException){
			$context !== null ?
				@unlink($temporaryFileName, $context) :
				@unlink($temporaryFileName);
			throw new \RuntimeException("Failed to write to temporary file $temporaryFileName: " . $filePutContentsException->getMessage(), 0, $filePutContentsException);
		}

		$renameTemporaryFileResult = $context !== null ?
			@rename($temporaryFileName, $fileName, $context) :
			@rename($temporaryFileName, $fileName);
		if(!$renameTemporaryFileResult){
			/*
			 * The following code works around a bug in Windows where rename() will periodically decide to give us a
			 * spurious "Access is denied (code: 5)" error. As far as I could determine, the fault comes from Windows
			 * itself, but since I couldn't reliably reproduce the issue it's very hard to debug.
			 *
			 * The following code can be used to test. Usually it will fail anywhere before 100,000 iterations.
			 *
			 * for($i = 0; $i < 10_000_000; ++$i){
			 *     file_put_contents('ops.txt.0.tmp', 'some data ' . $i, 0);
			 *     if(!rename('ops.txt.0.tmp', 'ops.txt')){
			 *         throw new \Error("something weird happened");
			 *     }
			 * }
			 */
			try{
                ($context !== null ?
                    copy($temporaryFileName, $fileName, $context) :
                    copy($temporaryFileName, $fileName));
			}catch(\ErrorException $copyException){
				throw new \RuntimeException("Failed to move temporary file contents into target file: " . $copyException->getMessage(), 0, $copyException);
			}
			@unlink($temporaryFileName);
		}
	}
}
