<?php
/**
 * Controller
 *
 * PHP Version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

namespace Pimf\Controller;
use Pimf\Controller\Base, Pimf\Registry;

/**
 * REST in PHP can be done pretty simple. Use this abstract controller for REST calls.
 * This works with Apache and Lighttpd out of the box, and no rewrite rules are needed.
 *
 * REST takes advantage of the HTTP request methods to layer itself
 * into the existing HTTP architecture.
 *
 * @package Controller
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
abstract class Rest extends Base
{
  protected $data = array();

  /**
   * Used to create a new object on the server.
   * Used to modify an existing object on the server.
   * Used to remove an object on the server.
   * @return mixed
   */
  abstract public function postAction();

  /**
   * Used for basic read requests to the server.
   * @return mixed
   */
  abstract public function getAction();

  public function init()
  {
    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");
  }

  public function indexAction()
  {
    $method = Registry::get('env')->getRequestMethod();

    if (!method_exists($this, $method.'Action')) {
      throw new \Exception("not supported REST method");
    }

    $this->data = $this->request->{'from'.ucfirst($method)}()->getAll();

    return call_user_func(array($this, $method.'Action'));
  }
}
