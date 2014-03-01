<?php
/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214                              
//$Id: site.php 1288 2012-07-15 04:11:20Z rovun $ 


// 用来显示 关于 版权 用户协议 招聘 等 
class site extends top
{ 

	function index(){
		$page = $this->spArgs('page');
		$attr = spClass('db_cpage_cate')->findPage($page);
		if(!$attr){
			header("HTTP/1.1 404 Not Found"); 
			header('Location:'. spUrl('main','index'));
		}

		$this->title = $attr['title'];
		$this->body = $attr['cpage_body']['body']; 
		$this->keyword = $attr['keyword'];
		$this->description = $attr['description'];
		$this->tags = $attr['tags'];

		$this->display('custompage.html');
	}
	
	

}
