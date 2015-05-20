<?php

require_once("init.php");

// ------------------------------------------------
// ------------------------------------------------
?>
<html>
	<head>
		<meta charset="utf-8">
		<title><?=$conf['title'] ?></title>
		<script src="jquery.min.js"></script>
		<script src="json2.js"></script>
		<link rel="shortcut icon" href="favicon.ico?v=20" />
		<link rel="stylesheet" href="style.css?<?=rand(0,1000)?>">
	</head>
	<body>
		
		<script>
		
			$(document).ready(function() {
				
				Engine.clearUI();
				
				// query ข้อมูลครั้งแรก
				Engine.doQuery();
				Engine.updateUI();
				Engine.updateTime();
				
				// ตั้งเวลา refresh ข้อมูล
				setInterval(function(){ Engine.updateUI(); }, <?=$conf['display']['interval_ui'] ?>);
				setInterval(function(){ Engine.doQuery(); }, <?=$conf['display']['interval_query'] ?>);
				setInterval(function(){ Engine.updateTime(); }, 1000);
			});
							
			var Engine = {
				busy : false,
				isWaitingRefreshUI: false,
				isFromQueryRefreshUI: false,
				toggle: false,
				displayArray: new Array(),
				displayPage: 1,
				doQuery: function(){
					
					if(Engine.busy) return;
					Engine.busy = true;
					// --------------------
					
					var post_message = '';
					$.ajax({
						url: 'query_mysql.php',
						type: 'POST',
						dataType: 'html',
						data: post_message,
						timeout: 0,
						error: function(){
							//alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
							Engine.busy = false;
							return;
						},
						success: function(data){
							
							try{
								//if(data > 0){ 
								//	location.reload();
								//	return;
								//}
								//alert('Error !! ' + data);
								if(data == undefined || data < 0){
									
									// error
									Engine.busy = false;
									
								}else{
									
									var tempArray = JSON.parse(data);
									
									<? if($conf['display']['is_show_past_event']){ ?>
									
										// แทนค่า ด้วย array ข้อมูลใหม่
										Engine.displayArray = tempArray;
										
									<? }else{ ?>
										
										Engine.displayArray = new Array();
										
										// กรองเอาเวลาที่ผ่านไปแล้วออก
										var now = new Date();
										var h = now.getHours();
										var mm = now.getMinutes();
										
										var calcCurrentTime = h * 60 + mm;
										
										// คำนวนเวลา
										for(var k=0; k<tempArray.length; k++){
											
											var  temp = tempArray[k].compare_time.toString();
											if(temp.length == 4) temp = '0'+ temp;
											var calcMinute = (+temp.substring(0,2)) * 60 + (+temp.substring(3,5));
											
											//onsole.log('compare',calcCurrentTime , calcMinute, temp);
											// เลยเวลาไปแล้ว ไม่นำมาแสดง
											if(calcCurrentTime > calcMinute + <?=$conf['display']['past_event_delay'] ?>) continue;
											//console.log('push');
											Engine.displayArray.push(tempArray[k]);
										}
									
									<? } ?>
									
									//console.log(Engine.displayArray);
									Engine.busy = false;
									
									// update UI
									if(Engine.isWaitingRefreshUI){
										Engine.updateUI();
										Engine.isFromQueryRefreshUI = true;
									}
								}
							} catch(err) { console.log(err);}
						}
					});
					
					// --------------------
				},
				clearUI : function(){
					
					$('#line1 .col').html('');
					$('#line2 .col').html('');
					$('#line3 .col').html('');
					$('#line4 .col').html('');
					$('#line5 .col').html('');
				},
				updateUI : function(){
					
					// ถ้ามีการ update UI หลัง query ให้รอก่อน 1 step กันการเปลี่ยนหน้าบ่อยเกินความจำเป็น
					if(Engine.isFromQueryRefreshUI){
						//console.log('wait next step');
						Engine.isFromQueryRefreshUI = false;
						return;
					}
					
					// รอให้ query ตอบกลับมาก่อนค่อย update UI
					if(Engine.busy){
						//console.log('wait until query done');
						Engine.isWaitingRefreshUI = true;
						return;
					}
					Engine.isWaitingRefreshUI = false;
					
					// เริ่มต้นการแสดงผล
					Engine.clearUI();
					if(Engine.displayArray == null) return;
					
					// คำนวณหน้าทีแสดงผล 
					var objCount = (Engine.displayArray.length);
					var totalPage = Math.ceil(objCount/5.0);
					if(totalPage == 0) totalPage = 1;
					
					Engine.displayPage++;
					if(Engine.displayPage < 1) Engine.displayPage = 1; 
					if(Engine.displayPage > totalPage) Engine.displayPage = 1;
					
					$('#countRecord').html(objCount);
					$('#pagging').html('หน้าที่ ' + Engine.displayPage + ' / ' + totalPage);
					
					//console.log(Engine.displayPage,totalPage);
					if(Engine.displayArray.length >= ((Engine.displayPage - 1)*5 + 1)){
						Engine.updateLine(1,Engine.displayArray[(Engine.displayPage - 1)*5 + 0]);
					}
					if(Engine.displayArray.length >= ((Engine.displayPage - 1)*5 + 2)){
						Engine.updateLine(2,Engine.displayArray[(Engine.displayPage - 1)*5 + 1]);
					}
					if(Engine.displayArray.length >= ((Engine.displayPage - 1)*5 + 3)){
						Engine.updateLine(3,Engine.displayArray[(Engine.displayPage - 1)*5 + 2]);
					}
					if(Engine.displayArray.length >= ((Engine.displayPage - 1)*5 + 4)){
						Engine.updateLine(4,Engine.displayArray[(Engine.displayPage - 1)*5 + 3]);
					}
					if(Engine.displayArray.length >= ((Engine.displayPage - 1)*5 + 5)){
						Engine.updateLine(5,Engine.displayArray[(Engine.displayPage - 1)*5 + 4]);
					}
				},
				updateLine: function(line,obj){
					try{
							
						$('#line'+line+' .col1').html(obj.field_1);
						$('#line'+line+' .col2').html(obj.field_2);
						$('#line'+line+' .col3').html(obj.field_3);
						$('#line'+line+' .col4').html(obj.field_4);
						$('#line'+line+' .col5').html(obj.field_5);
						$('#line'+line+' .col6').html(obj.field_6);
						$('#line'+line+' .col7').html(obj.field_7);
							
					} catch(err) { }
				},
				updateTime: function(){
					
					var now = new Date();
					
					var d = now.getDate();
					if(d.toString().length == 1) d = '0' + d;
					
					var m = (now.getMonth() + 1);
					var y = now.getFullYear();
					var h = now.getHours();
					
					var mm = now.getMinutes();
					if(mm.toString().length == 1) mm = '0' + mm;
					
					// นาฬิกา
					if(Engine.toggle){
						$('#clock').html('<div>'+h+'</div><div style="width:25px;">&nbsp;</div><div>'+ mm + '</div>');
						
					}else {
						$('#clock').html('<div>'+h+'</div><div style="width:25px;text-align:center;">:</div><div>'+ mm + '</div>');
					}
					Engine.toggle = !Engine.toggle;
					
					// วันที่
					if(m == 1) m = 'มกราคม';
					else if(m == 2) m = 'กุมภาพันธ์';
					else if(m == 3) m = 'มีนาคม';
					else if(m == 4) m = 'เมษายน';
					else if(m == 5) m = 'พฤษภาคม';
					else if(m == 6) m = 'มิถุนายน';
					else if(m == 7) m = 'กรกฎาคม';
					else if(m == 8) m = 'สิงหาคม';
					else if(m == 9) m = 'กันยายน';
					else if(m == 10) m = 'ตุลาคม';
					else if(m == 11) m = 'พฤศจิกายน';
					else if(m == 12) m = 'ธันวาคม';
				
					$('#currentDate').html(d + ' ' + m + ' ' + (y+543));
				}
			}
		</script>
	
		<table class="headertable">
			<tbody>
				<tr>
					<td class="col col1">&nbsp;</td>
					<td class="col col2 title" rowspan="2">คดีนัดพิจารณา วันที่  <span id="currentDate"></span> มีจำนวน <span id="countRecord"></span> คดี</td>
					<td class="col col3 clock" rowspan="2"><div id="clock"></div></td>
				</tr>
				<tr>
					<td class="col col1 pagging"><div id="pagging">หน้าที่ 1 / 2</td>
				</tr>
			</tbody>
		</table>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
			<div class="header">
				<div class="col col1">เลขคดี</div>
				<div class="col col2">โจทก์</div>
				<div class="col col3">จำเลย</div>
				<div class="col col4">เวลา</div>
				<div class="col col5">บังลังก์</div>
				<div class="col col6">ประเภทนัด</div>
				<div class="col col7">สถานะ</div>
			</div>
		</div>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
			<div id="line1" class="line line1">
				<div class="col col1 center"></div>
				<div class="col col2"></div>
				<div class="col col3"></div>
				<div class="col col4 center"></div>
				<div class="col col5 center"></div>
				<div class="col col6 center"></div>
				<div class="col col7 center"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line2" class="line line2">
				<div class="col col1 center"></div>
				<div class="col col2"></div>
				<div class="col col3"></div>
				<div class="col col4 center"></div>
				<div class="col col5 center"></div>
				<div class="col col6 center"></div>
				<div class="col col7 center"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line3" class="line line3">
				<div class="col col1 center"></div>
				<div class="col col2"></div>
				<div class="col col3"></div>
				<div class="col col4 center"></div>
				<div class="col col5 center"></div>
				<div class="col col6 center"></div>
				<div class="col col7 center"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line4" class="line line4">
				<div class="col col1 center"></div>
				<div class="col col2"></div>
				<div class="col col3"></div>
				<div class="col col4 center"></div>
				<div class="col col5 center"></div>
				<div class="col col6 center"></div>
				<div class="col col7 center"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line5" class="line line5">
				<div class="col col1 center"></div>
				<div class="col col2"></div>
				<div class="col col3"></div>
				<div class="col col4 center"></div>
				<div class="col col5 center"></div>
				<div class="col col6 center"></div>
				<div class="col col7 center"></div>
				<div style="clear:both"></div>
			</div>
		</div>
	</body>
</html>