<?php
/*
 * CPayeer
 * Version: 1.0
 * Date: April 29th, 2013
 */
class CPayeer
{
	private $url = 'https://payeer.com/ajax/api/api.php';
	private $agent = 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0';

	private $auth = array();

	private $output;
	private $errors;
	private $language = 'ru';

	public function __construct($account, $apiId, $apiPass)
	{
		$arr = array(
			'account' => $account,
			'apiId' => $apiId,
			'apiPass' => $apiPass,
		);
		$response = $this->getResponse($arr);
		if (!empty($response['errors']))
		{
			$this->errors = $response['errors'];
		}
		if ($response['auth_error'] == '0')
		{
			$this->auth = $arr;
		}

	}

	public function isAuth()
	{
		if (!empty($this->auth)) return true;
		return false;
	}

	private function getResponse($arPost)
	{
		if (!function_exists('curl_init'))
		{
            die('curl library not installed');
            return false;
        }

		if ($this->isAuth())
		{
			$arPost = array_merge($arPost, $this->auth);
		}
		$data = array();
		foreach ($arPost as $k => $v)
		{
			$data[] = urlencode($k) . '=' . urlencode($v);
        }
		$data[] = 'language=' . $this->language;
		$data = implode('&', $data);

        $handler  = curl_init();
        curl_setopt($handler, CURLOPT_URL, $this->url);
        curl_setopt($handler, CURLOPT_HEADER, 0);
        curl_setopt($handler, CURLOPT_POST, true);
        curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handler, CURLOPT_USERAGENT, $this->agent);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($handler);
		//print_r($content);
		$arRequest = curl_getinfo($handler);
		//print_r($arRequest);
		curl_close($handler);
		if (strpos($content, '{') !== false) $content = $this->objectToArray(json_decode($content));
        return $content;
	}

	private function objectToArray($ob)
	{
		$arr = array();
		foreach ($ob as $k => $v)
		{
			if (is_object($v))
			{
				$arr[$k] = $this->objectToArray($v);
			}
			else
			{
				$arr[$k] = $v;
			}
		}
		return $arr;
	}

	public function getPaySystems()
	{
		$arPost = array(
			'action' => 'getPaySystems',
		);
		$response = $this->getResponse($arPost);
		return $response;
	}

	public function initOutput($arr)
	{
		$arPost = $arr;
		$arPost['action'] = 'initOutput';
		$response = $this->getResponse($arPost);
		if (empty($response['errors']))
		{
			$this->output = $arr;
			return true;
		}
		else
		{
			$this->errors = $response['errors'];
		}
		return false;
	}

	public function output()
	{
		$arPost = $this->output;
		$arPost['action'] = 'output';
		$response = $this->getResponse($arPost);
		if (empty($response['errors']))
		{
			return $response['historyId'];
		}
		else
		{
			$this->errors = $response['errors'];
		}
		return false;
	}

	public function getHistoryInfo($historyId)
	{
		$arPost = array(
			'action' => 'historyInfo',
			'historyId' => $historyId
		);
		$response = $this->getResponse($arPost);
		return $response;
	}

	public function getBalance()
	{
		$arPost = array(
			'action' => 'balance',
		);
		$response = $this->getResponse($arPost);
		return $response;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function transfer($arPost)
	{
		$arPost['action'] = 'transfer';
		$response = $this->getResponse($arPost);
		return $response;
	}
	public function SetLang($language)
	{
		$this->language = $language;
		return $this;
	}
}
?>