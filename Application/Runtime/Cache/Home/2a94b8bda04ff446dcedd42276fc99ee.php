<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		
            <title>QAQ数码商城首页</title>
        
        <meta charset="utf-8" />
		
		<link rel="stylesheet" href="/QAQproject/Public/Home/css/bootstrap.min.css">
		<link rel="stylesheet" href="/QAQproject/Public/Home/css/base.css">
		<script src="/QAQproject/Public/Home/js/jquery-1.10.2.min.js"></script>
		
        <!-- 预留接口，等子模板使用 -->
        
		<style>
			.gonggao{
				width:240px;
				height:410px;
				float:right;
			}
			.gonggao div{
				width:240px;
				height:200px;
				border-left:1px solid #EDEDED;
				border-top:1px solid #EDEDED;
				border-bottom:1px solid #EDEDED;
			}
			.gonggao div ul li{
				list-style:none;
			}
			.turnimg{
				width:750px;
				height:410px;
				float:right;
				border:1px solid red;
				margin-right:10px;
			}
			#catenav{
				display:block;
			}
		</style>
	
	</head>
	
	<body>
		<!-- ======页面头部======= -->
		<div>
			<div class="topbar">
				<div class="topbar_content">
					<div>
						<span><a href="">商城首页</a></span>
						<span>Hi~欢迎来到QAQ数码商城，请<a href="">登录</a></span>
						<span><a href="">免费注册</a></span>
					</div>
					
					<ul>
						<li><a href="">我的订单</a></li>
						<li><a href="">个人中心</a></li>
						<li><a href="">购物车</a></li>
						<li>联系客服</li>
					</ul>
				</div>
			</div>
			
			<div class="container">
				<div class="top_search">
					<div class="logo">
						<img src="/QAQproject/Public/img/logo.jpg" alt="QAQ商城logo" style="width:180px;height:50px;"/>
						<span>QAQ在线网上数码商城</span>
					</div>
					<div>
						<form action="" method="post">
							<div class="search">
								<input type="text" name="keyword" />
								<input type="submit" value="搜索" hidefocus="true" />
							</div>					
						</form>
						<ul>
							<li><a href="">##########</a></li>
							<li><a href="">####</a></li>
							<li><a href="">####</a></li>
							<li><a href="">###</a></li>
							<li><a href="">###</a></li>
							<li><a href="">###</a></li>
						</ul>
					</div>
					<div>
						<img src="/QAQproject/Public/img/ChMkJlYcpziIdMcxAAB2sdYBFdcAADnzQKpVyIAAHbJ372.jpg" style="width:180px;height:60px;margin-left:55px;"/>
					</div>
				</div>
				
				<ul class="nav nav-tabs">
					<li role="presentation" style="height:41px;" class="active"><span>商品分类</span></li>
					<li role="presentation"><a href="#">首页</a></li>
					<li role="presentation"><a href="#">Messages</a></li>
				</ul>
				<div>
					<div id="catenav">
						<ul class="catenav" style="margin-bottom:0px;padding-left:0px;">
							<li class="navnav">手机</li>
							<li>电脑</li>
							<li>摄影</li>
						</ul>
						
						<img src="/QAQproject/Public/img/ChMkJ1Z7xxmIJIRaAAAVx_5H8MsAAGjZwDgKK0AABXf118.gif" />
					</div>
					
	
		<div class="gonggao">
			<img src="/QAQproject/Public/img/baozhang20160320.jpg" />
			<div>
				<p align="center" style="margin-top:20px;">商城公告</p>
				<ul>
					
					<li>遍历</li>
					<li>遍历</li>
					<li>遍历</li>
				</ul>
			</div>
		</div>
		
		<!--==============轮播图=================-->
		<div class="turnimg">
			
			
		</div>
		
		
	
				</div>
					</div>
					<div class="navnone">
						
			</div>
		</div>
		
		<!-- ======主体部分接口======= -->
        
	
	
		
		<!-- ========页面尾部========== -->
        <hr style="color:#3d3d3d;" />
		
		<div class="weibu">
			<ul>
				<li><i></i>行货保证</li>
				<li><i></i>发票保障</li>
				<li><i></i>48小时发货</li>
				<li><i></i>顺丰包邮</li>
				<li><i></i>无忧退换</li>
				<li><i></i>先行赔付</li>
			</ul>
		</div>
		
		<hr style="color:#3d3d3d;width:1200px;" />
		<div class="footer">
			<ul>
				<li><b>购物指南</b></li>
				<li>账号注册</li>
				<li>购物流程</li>
				<li>付款方式</li>
			</ul>
			<ul>
				<li><b>售后服务</b></li>
				<li>先行赔付</li>
				<li>退款退货流程</li>
				<li>投诉举报</li>
			</ul>
			<ul>
				<li><b>商城保障</b></li>
				<li>行货保障</li>
				<li>如实描述</li>
				<li>物流配送</li>
				<li>48小时发货</li>
				<li>发票保障</li>
			</ul>
			<ul>
				<li><b>商家服务</b></li>
				<li>商家中心</li>
				<li>商城规则</li>
				<li>商家帮助</li>
			</ul>
		</div>
	</body>
	
</html>	

<script>
	$(
		$('li.navnav').mouseover(function(){
			//console.log('1');
			$(this).css({'background':'white','color':'black',})
			$('.navnone').css('display','block');
		}).mouseout(function(){
			$(this).css({'background':'#2D2D2D','color':'white',})
			$('.navnone').css('display','none');
		}).next().mouseover(function(){
			//console.log('1');
			$(this).css({'background':'white','color':'black',})
			$('.navnone').css('display','block');
		}).mouseout(function(){
			$(this).css({'background':'#2D2D2D','color':'white',})
			$('.navnone').css('display','none');
		}).next().mouseover(function(){
			//console.log('1');
			$(this).css({'background':'white','color':'black',})
			$('.navnone').css('display','block');
		}).mouseout(function(){
			$(this).css({'background':'#2D2D2D','color':'white',})
			$('.navnone').css('display','none');
		})
	)
		$('.active').mouseover(function(){
			//console.log('1');
			$('#catenav').css('display','block');
			
		}).mouseout(function(){
			$('#catenav').css('display','none');
		})
		
		
	
</script>


		<script>
			$(
				$('.active').unbind()
			);
		</script>