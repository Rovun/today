<?php
/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214                             
//$Id: blog.php 772 2012-06-12 14:20:42Z rovun $ 


//博客动作执行
class invite extends top
{

	function index(){
		
		
	}
	
	function myintval(){
		if(!$this->uid >0){prient_jump(spUrl('main'));}	
		$this->memberinfo();
		
		$this->display('user_invite.html');
	
	}
	
	

	

}
