<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Welcome extends Controller
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return Response::forge(View::forge('welcome/index'));
	}
	
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 * tho can
	 */
	public function action_thocan()
	{
		return Response::forge(View::forge('welcome/thocan'));
	}
	
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 * cantho
	 */
	public function action_cantho()
	{
		return Response::forge(View::forge('welcome/cantho'));
	}
	
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 * cantho
	 */
	public function action_canviecgap()
	{
		return Response::forge(View::forge('welcome/canviecgap'));
	}
	
	public function action_canthogap()
	{
		return Response::forge(View::forge('welcome/canthogap'));
	}
	
	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
	
	public function action_hello2()
	{
		returnView::forge('welcome/hello2');
	}
	 
}
