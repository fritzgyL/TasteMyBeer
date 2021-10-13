<?php
abstract class BaseController
{

	protected $title;
	protected $description;
	protected $h1;
	protected $useLayout = true;
	protected $breadCrumbs = array();

	public function __construct()
	{
	}

	function getTitle()
	{
		return $this->title;
	}

	function getDescription()
	{
		return $this->description;
	}

	function getH1()
	{
		return $this->h1;
	}

	function useLayout()
	{
		return $this->useLayout;
	}
}
