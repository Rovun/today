<?php
/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214                             
//$Id: db_notice.php 1309 2012-07-22 03:21:13Z rovun $ 

//通知发送
//系统通知级别 0 用户私信  1 评论通知  2 系统通知 3关注通知
class db_notice extends ybModel  
{  
	var $pk = "id"; 
	var $table = "notice"; // 数据表的名称
	var $linker = array(  
		 array(  
             'type' => 'hasone',   // 关联类型，这里是一对一关联  
            'map' => 'user',    // 关联的标识  
             'mapkey' => 'uid', // 本表与对应表关联的字段名  
             'fclass' => 'db_member', // 对应表的类名  
            'fkey' => 'uid',    // 对应表中关联的字段名
			'field'=>'uid,username,domain ',//你要限制的字段     
            'enabled' => true     // 启用关联  
        )
		

    );  

	/*评论回复列表*/
	function noticeReplay($row,$title,$msg='')
	{
		$this->create(array('uid'=>$_SESSION['uid'],'sys'=>'1','foruid'=>$row['foruid'],'title'=>$title,'info'=>$msg,'time'=>time(),'location'=>'blog|'.$row['bid'],'time'=>time()));
		$this->sendReplay($_SESSION['uid'],$row['foruid'],$msg,$row['bid']);
	}

	/*关注通知*/
	function noticeFollow($uid,$imuid,$is=1){
		if($is ==1){
			$this->noticeReady($imuid,3,$uid,'关注通知','关注了你','user|'.$uid);
			$this->sendFollow($uid,$imuid);
		}else{
			$this->noticeReady($imuid,3,$uid,'关注通知','互相关注','user|'.$uid);
			$this->sendFollow($uid,$imuid);
		}
	}
	
	/*审核提示发送*/
	function blogverify($uid){
		$title = '发布内容通知';
		$info = '您发布的内容正在人工审核，在审核完成之前将不会在首页显示。';
		$this->noticeReady(0,2,$uid,$title,$info,'');
	}
	
	/*creaty ready*/
	/*location formay gotomod|id*/
	private function noticeReady($uid,$type,$imuid,$title,$info,$location)
	{
		return $this->create(array('uid'=>$uid,'sys'=>$type,'foruid'=>$imuid,'title'=>$title,'info'=>$info,'location'=>$location,'time'=>time()));
	}
	
	
	/*	给用户发送系统邮件
		params $uid_array array
	*/
	function noticeSystem($uid_array,$msg,$imuid){
		foreach($uid_array as $uid){
			$this->noticeReady($uid,2,$imuid,'系统通知',$msg,'user|'.$uid);
		}
	}
	
	/*发送评论邮件*/
	function sendReplay($imuid,$uid,$msga,$bid)
	{
		if($uid == $_SESSION['uid']) {return true;}
		$rs = spClass('db_member')->find(array('uid'=>$uid));
		$for = spClass('db_member')->find(array('uid'=>$imuid));
		if($rs['m_rep'] != 1) return true;
		$title = '亲爱'.$rs['username'].',您在'.$GLOBALS['YB']['site_title'].'的轻博客回复通知';
		$uri = trim(dirname($GLOBALS['G_SP']['url']['url_path_base']),'\/\\');
		if( '' == $uri ){ $uri = 'http://'.$_SERVER['HTTP_HOST']; 	}else{ $uri = 'http://'.$_SERVER['HTTP_HOST'].'/'.$uri; }
		@preg_match("/\[at=(.*?)](.*?)\[\/at\]/i",$msga,$msg); //$msg[1]
		$msg = str_replace($msg[0],'<a href="'.$uri['dirname'].goUserHome(array('uid'=>$msg[1])).'" target="_blank">'.$msg[2].'</a>',$msga); //处理邮件中的at
		$tpl = '<h3>亲爱的'.$rs['username'].'：</h3>
			    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
				    <tr>
					    <td width="50" valign="top"><img src="'.$uri.avatar(array('uid'=>$imuid,'size'=>'small')).'" align="bottom"/></td>
						<td>
						    <p>'.$for['username'].'回复了您的轻博客：</p>
						    <p>"'.$msg.'"</p><a href="'.$uri.goUserBlog(array('bid'=>$bid)).'" target="_blank">你可以立即查看您的轻博</a></p>
						</td>
					</tr>
				</table>';
				
		$body = $this->sendmailTemplate($title,$tpl);
		$this->emailReady($rs['email'],$title,$body);
	}
	
	/*发送注册邮件*/
	function sendRegisgtr($uid)
	{
		$rs = spClass('db_member')->find(array('uid'=>$uid));
		$title = '亲爱的会员,这是一封来自'.$GLOBALS['YB']['site_title'].'的注册通知';
		$tpl = '<h3>亲爱的'.$rs['username'].'：</h3>
			    <p>您使用邮箱：'.$rs['email'].',在'.$GLOBALS['YB']['site_title'].'注册,祝您使用愉快。</p><p>'.date('Y-m-d H:i',time()).'</p>';
		$body = $this->sendmailTemplate($title,$tpl);
		$this->emailReady($rs['email'],$title,$body);
	}
	
	
	/*发送找会密码邮件*/
	function sendFindPwd($row,$url)
	{
		$rs = spClass('db_member')->find(array('uid'=>$row['uid']));
		$title = '亲爱的会员,这是一封来自'.$GLOBALS['YB']['site_title'].'的密码找回通知';
		$tpl = '<h3>亲爱的'.$rs['username'].'：</h3>
			    <p>您使用邮箱：'.$rs['email'].',在'.$GLOBALS['YB']['site_title'].'上执行了找回密码操作。</p>
		<p>请您打开或复制下面的地址来验证您的操作，然后重置您的密码</p>
		<p class="red"><a href="'.$url.'" target="_blank">'.$url.'</a></p>
		<p>您的本次验证操作将在'.date('Y-m-d H:i',$row['time']).' 后过期，过期后请重新执行密码找回步骤</p>
		<p
