<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Хранилище файлов
 */
class  FileStorage {

	private $CI;
	
	function FileStorage()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('file');
		$this->CI->load->helper('string');
	}
	
	/**
	 * Создать файл из случайных символов длинной 32 байта
	 * @param string $basePath - базовый путь к каталогу где необходимо создать файл
	 * @param string $data - данные для записи в файл
	 * @return string|boolean - false- не удалось создать файл, либо полный путь к файлу в случае успеха
	 */
	public static function createFile($basePath, $data)
	{
		$path=$basePath.random_string('unique');
		if(write_file($path, $data))
		{
			return $path;
		}
		return false;
	}
	
	/**
	 * Удалить файлы
	 * @param array $files - список файлов
	 */
	public static function deleteFiles(array $files)
	{
		foreach ($files as $item)
		{
			unlink($item);
		}
	}
}