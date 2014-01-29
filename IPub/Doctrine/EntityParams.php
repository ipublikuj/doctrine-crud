<?php
/**
 * EntityParams.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine;

use \Doctrine\ORM\Mapping as ORM;

trait EntityParams
{
	/**
	 * @ORM\Column(type="json_array", name="params", nullable=TRUE)
	 */
	protected $params;

	public function setParams($params)
	{
		$this->params = $params;

		return $this;
	}

	public function getParams()
	{
		return $this->params;
	}

	function setParam($key, $value = '')
	{
		$keys = explode('.', $key);

		if ( count($keys) > 1 ) {
			$val = &$this->params;
			$last = array_pop($keys);

			foreach ($keys as $key) {
				if ( !isset($val[$key]) || !is_array($val[$key]) )
					$val[$key] = array();

				$val = &$val[$key];
			}

			$val[$last] = $value;

		} else {
			$this->params[$keys[0]] = $value;
		}

		return $this;
	}

	function getParam($key, $default = NULL)
	{
		$keys = explode('.', $key);

		if ( array_key_exists($keys[0], $this->params) ) {
			if ( is_array($this->params[$keys[0]]) ) {
				$val = NULL;

				foreach ($keys as $key) {
					if ( isset($val) )
						if ( isset($val[$key]) )
							$val = $val[$key];
						else
							$val = NULL;
					else
						$val = isset($this->params[$key]) ? $this->params[$key] : $default;

				}

				return (isset($val) ? $val : $default);

			} else {
				return trim($this->params[$keys[0]]);
			}
		}

		return $default;
	}
}