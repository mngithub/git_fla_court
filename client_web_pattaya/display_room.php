<?php
// ------------------------------------------------
// ------------------------------------------------
// INIT
// ------------------------------------------------
// ------------------------------------------------

$conf['title']			 		= 'title';
$conf['interval_query'] 		= 10000;

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
				Engine.updateTime();
				
				// ตั้งเวลา refresh ข้อมูล
				setInterval(function(){ Engine.doQuery(); }, <?=$conf['interval_query'] ?>);
				setInterval(function(){ Engine.updateTime(); }, 1000);
			});
							
			var Engine = {
				busy : false,
				toggle: false,
				displayArray: new Array(),
				displayPage: 1,
				doQuery: function(){
					
					if(Engine.busy) return;
					Engine.busy = true;
					// --------------------
					
					var post_message = 'r=<?=$_REQUEST['r'] ?>';
					$.ajax({
						url: 'query_mysql_room.php',
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
								
								console.log(data);
								
								if(data == undefined || data < 0){
									
									// error
									Engine.busy = false;
									
								}else{
									
									var tempArray = JSON.parse(data);
									
				
									// แทนค่า ด้วย array ข้อมูลใหม่
									Engine.displayArray = tempArray;
									
									//console.log(Engine.displayArray);
									Engine.busy = false;
									
									// update UI
									Engine.updateUI();
								}
							} catch(err) { console.log(err);}
						}
					});
					
					// --------------------
				},
				clearUI : function(){
					
					$('#line1 .col2').html('');
					$('#line2 .col2').html('');
					$('#line3 .col2').html('');
					$('#line4 .col2').html('');
					$('#line5 .col2').html('');
					
					$('#next .col2').html('');
				},
				updateUI : function(){
					
					// เริ่มต้นการแสดงผล
					Engine.clearUI();
					if(Engine.displayArray == null) return;
					
					if(Engine.displayArray.length >= 1){
						Engine.updateLine(Engine.displayArray[0]);
					}
				},
				updateLine: function(obj){
					try{
						
						$('#line1 .col2').html(obj.field_1);
						$('#line2 .col2').html(obj.field_2);
						$('#line3 .col2').html(obj.field_3);
						$('#line4 .col2').html(obj.field_4);
						$('#line5 .col2').html(obj.field_5);
							
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
					<td class="col col2 title" rowspan="2">ห้องเลขที่  <?=htmlspecialchars($_REQUEST['r']) ?></td>
					<td class="col col3 clock" rowspan="2"><div id="clock"></div></td>
				</tr>
				<tr>
					<td class="col col1 pagging">&nbsp;</td>
				</tr>
			</tbody>
		</table>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
			<div class="header">
				<div class="col col1">ข้อมูลคดี</div>
			</div>
		</div>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
			<div id="line1" class="line line1">
				<div class="col col1">เลขคดีดำที่</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line2" class="line line2">
				<div class="col col1">โจทก์</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line3" class="line line3">
				<div class="col col1">จำเลย</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line4" class="line line4">
				<div class="col col1">ข้อหา</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
			<div id="line5" class="line line5">
				<div class="col col1">วันที่ฟ้อง</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
		</div>
		
		<div class="nexttable">
			<div id="next" class="line next">
				<div class="col col1">คดีถัดไป</div>
				<div class="col col2"></div>
				<div style="clear:both"></div>
			</div>
		</div>
		
		<table class="footertable">
			<tbody>
				<tr>
					<td class="col col1">&nbsp;</td>
					<td class="col col2 title" rowspan="2"><span id="currentDate"></span></td>
					<td class="col col3 clock" rowspan="2"><div id="clock"></div></td>
				</tr>
				<tr>
					<td class="col col1 pagging">&nbsp;</td>
				</tr>
			</tbody>
		</table>
		
	</body>
</html>