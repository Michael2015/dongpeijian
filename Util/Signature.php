<?php
namespace Golo;

use Lavender\Exception;
/**
* 根据请求信息通过固有算法获取签名
*/
class Signature
{
	/**
	 * 加密key
	 * @var string
	 */
	private $_key = '';
	/**
	 * 加密方式
	 * @var string
	 */
	private $_sign_type = 'md5';
	/**
	 * charset
	 * @var string
	 */
	private $_input_charset = 'utf-8';

	/**
	 * 需要过滤掉的参数数组
	 * eg. array('sign' => 1, 'sign_type' => 1)
	 * @var array
	 */
	private $_filter_params = array();

	/**
	 * 构造方法
	 * @param string $key 密钥
	 * @param string $sign_key 加密方式
	 * @param array  $filters  要过滤的参数数组
	 * @param string $charset  字符编码
	 */
	public function __construct($key = '', $sign_type = '', $filters = array(), $charset = '')
	{
		if (!empty($key)) {
			$this->_key = $key;
		}
		if (!empty($sign_type)) {
			$this->_sign_type = $sign_type;
		}
		if (!empty($filters) && is_array($filters)) {
			$this->_filter_params = $filters;
		}
		if (!empty($charset)) {
			$this->_input_charset = $charset;
		}
	}
	/**
	 * 获取签名
	 */
	public function get_signature($request_param)
	{
		if(!is_array($request_param)){
			throw new Exception("WRONG PARAMS", 1);
			
		}
		//过滤掉空值、sign与sign_type参数并将传递传递的参数排序后转换成URI参数的形式 （eg. key=value&key1=value1...）
		$params = $this->filter_param($request_param);
		ksort($params);
		$uri_params = $this->create_linkstring($params);
		//计算签名
		return $this->sign($uri_params.$this->_key, $this->_sign_type);		
	}

	/**
	 * 除去数组中的空值和签名参数
	 * @param  array $request_param 请求的数组参数
	 * @return array 
	 */
	public function filter_param($request_param)
	{
		$params = array();
		foreach ($request_param as $key => $value) {
			//if(isset($this->_filter_params[$key]) || empty($value) || in_array($this->_filter_params)){
			if(isset($this->_filter_params[$key]) || (empty($value) && $value !== 0  && $value !== '0')){
				continue;
			}
			$params[$key] = $value;
		}
		return $params;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param array $array
	 * @return string
	 */
	public function create_linkstring($array)
	{
		$uri_param = '';
		if(!is_array($array)){
			return $uri_param;
		}
		foreach ($array as $key => $value) {
			$uri_param .= $key . '=' . $value . "&";
		}
		return rtrim($uri_param, '&');
	}

	/**
	 * 计算签名
	 * @param string $encryption_string 要转换成签名的字符串
	 * @return string
	 */
	public function sign($encryption_string)
	{
		// if(function_exists('mb_convert_encoding')){

		// 	$encryption_string = mb_convert_encoding($encryption_string, $this->_input_charset);
		// }
		return md5($encryption_string);
	}
}
?>
