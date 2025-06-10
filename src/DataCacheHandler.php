<?php

namespace cjrasmussen\DataCacheHandler;

use JsonException;
use RuntimeException;

class DataCacheHandler
{
	private string $cacheDirPath;
	private ?string $cacheFilePath = null;

	public function __construct(string $cache_dir_path)
	{
		if (!is_dir($cache_dir_path)) {
			throw new RuntimeException('Cache directory does not exist');
		}

		if (substr($cache_dir_path, -1) !== DIRECTORY_SEPARATOR) {
			$cache_dir_path .= DIRECTORY_SEPARATOR;
		}

		$this->cacheDirPath = $cache_dir_path;
	}

	/**
	 * Initialize the data cache
	 *
	 * @param string $script_path
	 * @return $this
	 */
	public function initialize(string $script_path): self
	{
		$hash = md5($script_path);
		$this->cacheFilePath = $this->cacheDirPath . $hash;

		if (!file_exists($this->cacheFilePath)) {
			touch($this->cacheFilePath);
		}

		return $this;
	}

	/**
	 * Get the path to the cache file for the initialized instance
	 *
	 * @return string|null
	 */
	public function getCacheFilePath(): ?string
	{
		return $this->cacheFilePath;
	}

	/**
	 * Read string data from a data cache file
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	public function read(): string
	{
		$contents = file_get_contents($this->cacheFilePath);
		if ($contents === false) {
			throw new RuntimeException('Cache file could not be loaded.');
		}

		return $contents;
	}

	/**
	 * Read JSON data from a data cache file
	 *
	 * @return array
	 * @throws JsonException
	 */
	public function readJson(): array
	{
		$data = $this->read();
		if (!$data) {
			return [];
		}

		$json = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
		if (!$json) {
			return [];
		}

		return $json;
	}

	/**
	 * Read serialized data from a data cache file
	 *
	 * @return array
	 */
	public function readSerialized(): array
	{
		$data = $this->read();
		if (!$data) {
			return [];
		}

		$return = unserialize($data, ['allowed_classes' => false]);
		if (!$return) {
			return [];
		}

		return $return;
	}

	/**
	 * Write string data to a data cache file
	 *
	 * @param string $data
	 */
	public function write(string $data): void
	{
		file_put_contents($this->cacheFilePath, $data);
	}

	/**
	 * Write JSON data to a data cache file
	 *
	 * @param array $data
	 * @throws JsonException
	 */
	public function writeJson(array $data): void
	{
		$string = json_encode($data, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
		$this->write($string);
	}

	/**
	 * Write serialized data to a data cache file
	 *
	 * @param array $data
	 */
	public function writeSerialized(array $data): void
	{
		$string = serialize($data);
		$this->write($string);
	}
}
