<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Caching
 */



/**
 * Output caching helper.
 *
 * @author     David Grudl
 * @package Nette\Caching
 */
class NCachingHelper extends NObject
{
	/** @var array */
	public $dependencies;

	/** @var NCache */
	private $cache;

	/** @var string */
	private $key;



	public function __construct(NCache $cache, $key)
	{
		$this->cache = $cache;
		$this->key = $key;
		ob_start();
	}



	/**
	 * Stops and saves the cache.
	 * @param  array  dependencies
	 * @return void
	 */
	public function end(array $dp = NULL)
	{
		if ($this->cache === NULL) {
			throw new InvalidStateException('Output cache has already been saved.');
		}
		$this->cache->save($this->key, ob_get_flush(), (array) $dp + (array) $this->dependencies);
		$this->cache = NULL;
	}

}
