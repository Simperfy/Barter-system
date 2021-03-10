<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class AjaxFilter implements FilterInterface
{
	public $response;

	use ResponseTrait;
	/**
	 * Do whatever processing this filter needs to do.
	 * By default it should not return anything during
	 * normal execution. However, when an abnormal state
	 * is found, it should return an instance of
	 * CodeIgniter\HTTP\Response. If it does, script
	 * execution will end and that Response will be
	 * sent back to the client, allowing for error pages,
	 * redirects, etc.
	 *
	 * @param RequestInterface|mixed $request
	 * @param array|null       $arguments
	 *
	 * @return mixed
	 */
	public function before($request, $arguments = null)
	{
		$this->response = \Config\Services::response();
		if (!$request->isAjax()) return $this->respond(['error' => 'Method not allowed'], 405);
	}

	/**
	 * Allows After filters to inspect and modify the response
	 * object as needed. This method does not allow any way
	 * to stop execution of other after filters, short of
	 * throwing an Exception or Error.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface|mixed $response
	 * @param array|null        $arguments
	 *
	 * @return mixed
	 */
	public function after($request, $response, $arguments = null)
	{
	}
}