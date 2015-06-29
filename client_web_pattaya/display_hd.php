<?php
// ------------------------------------------------
// ------------------------------------------------
// INIT
// ------------------------------------------------
// ------------------------------------------------

$conf['title']			 		= 'ศาลแขวงพัทยา';

$conf['interval_ui'] 			= 10000;
$conf['interval_query'] 		= 60000;

// แสดงข้อมูลที่ผ่านไปแล้วหรือไม่
$conf['is_show_past_event'] 	= 1;
// ข้อมูลผ่านไปแล้วกี่นาที จึงจะไม่แสดง
$conf['past_event_delay'] 		= 10;

// ------------------------------------------------
// ------------------------------------------------

if(isset($_REQUEST['r'])){
	$r = trim($_REQUEST['r']);
}else{
	
	// default room
	$r = '';
}
?>
<html>
	<head>
		<meta charset="utf-8">
		
		<title><?=$conf['title'] ?></title>
		<script src="jquery.min.js"></script>
		<script src="json2.js"></script>
		<link rel="shortcut icon" href="favicon.ico?v=20" />
		<link rel="stylesheet" href="display_hd.css?<?=rand(0,1000)?>">
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
				setInterval(function(){ Engine.updateUI(); }, <?=$conf['interval_ui'] ?>);
				setInterval(function(){ Engine.doQuery(); }, <?=$conf['interval_query'] ?>);
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
									
									<? if($conf['is_show_past_event']){ ?>
										
										Engine.displayArray = new Array();
										
										// แทนค่า ด้วย array ข้อมูลใหม่
										for(var k=0; k<tempArray.length; k++){
											if('<?php echo addslashes($r); ?>' != '' 
												&& tempArray[k].field_5.toString() != '<?php echo addslashes($r); ?>') continue;
											Engine.displayArray.push(tempArray[k]);
										}
										
									<? }else{ ?>
										
										Engine.displayArray = new Array();
										
										// กรองเอาเวลาที่ผ่านไปแล้วออก
										var now = new Date();
										var h = now.getHours();
										var mm = now.getMinutes();
										
										var calcCurrentTime = h * 60 + mm;
										
										// คำนวนเวลา
										for(var k=0; k<tempArray.length; k++){
											
											if('<?php echo addslashes($r); ?>' != ''  
												&& tempArray[k].field_5.toString() != '<?php echo addslashes($r); ?>') continue;
			
											var  temp = tempArray[k].compare_time.toString();
											if(temp.length == 4) temp = '0'+ temp;
											var calcMinute = (+temp.substring(0,2)) * 60 + (+temp.substring(3,5));
											
											//onsole.log('compare',calcCurrentTime , calcMinute, temp);
											// เลยเวลาไปแล้ว ไม่นำมาแสดง
											if(calcCurrentTime > calcMinute + <?=$conf['past_event_delay'] ?>) continue;
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
					$('#countRecordEN').html(objCount);
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
					var mStr = '';
					if(m == 1) mStr = 'มกราคม';
					else if(m == 2) mStr = 'กุมภาพันธ์';
					else if(m == 3) mStr = 'มีนาคม';
					else if(m == 4) mStr = 'เมษายน';
					else if(m == 5) mStr = 'พฤษภาคม';
					else if(m == 6) mStr = 'มิถุนายน';
					else if(m == 7) mStr = 'กรกฎาคม';
					else if(m == 8) mStr = 'สิงหาคม';
					else if(m == 9) mStr = 'กันยายน';
					else if(m == 10) mStr = 'ตุลาคม';
					else if(m == 11) mStr = 'พฤศจิกายน';
					else if(m == 12) mStr = 'ธันวาคม';
				
					$('#currentDate').html(d + ' ' + mStr + ' ' + (y+543));
					
					if(m == 1) mStr = 'January';
					else if(m == 2) mStr = 'February';
					else if(m == 3) mStr = 'March';
					else if(m == 4) mStr = 'April';
					else if(m == 5) mStr = 'May';
					else if(m == 6) mStr = 'June';
					else if(m == 7) mStr = 'July';
					else if(m == 8) mStr = 'August';
					else if(m == 9) mStr = 'September';
					else if(m == 10) mStr = 'October';
					else if(m == 11) mStr = 'November';
					else if(m == 12) mStr = 'December';
					
					$('#currentDateEN').html(d + ' ' + mStr + ' ' + (y));
				}
			}
		</script>
	
		<table class="headertable">
			<tbody>
				<tr>
					<td class="col col1" rowspan="2">&nbsp;</td>
					<td class="col col0" colspan="3" align="center">
						<?php if($r == ''){ ?>
						ห้องพิจารณาคดี
						<?php }else{ ?>
						ห้องพิจารณาคดีที่ <?php echo htmlspecialchars($r); ?>
						
						<?php } ?>
					</td>
					<td class="col col5" rowspan="2">&nbsp;</td>
				</tr>
				<tr>
				
					<td class="col col2">
						คดีนัดพิจารณา
						<div class="subtitle">Hearing List</div>
					</td>
					<td class="col col3">
						<div>วันที่  <span id="currentDate">&nbsp;</span></div>
						<div class="subtitle"><span id="currentDateEN">&nbsp;</span></div>
					</td>
					<td class="col col4">
						จำนวน <span id="countRecord"></span> คดี
						<div class="subtitle">Totaling <span id="countRecordEN"></span> Case(s)</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
			<div class="header">
				<div class="col col1">
					เลขคดี
					<div class="subtitle">Case No.</div>
				</div>
				<div class="col col2">
					โจทก์
					<div class="subtitle">Plaintiffs</div>
				</div>
				<div class="col col3">
					จำเลย
					<div class="subtitle">Defendants</div>
				</div>
				<div class="col col4">
					เวลา
					<div class="subtitle">Time</div>
				</div>
				<div class="col col5">
					บัลลังก์
					<div class="subtitle">Court Room</div>
				</div>
				<div class="col col6">
					ประเภทนัด
					<div class="subtitle">Subject</div>
				</div>
				<div class="col col7">
					สถานะ
					<div class="subtitle">Hearing Status</div>
				</div>
			</div>
		</div>
		
		<div style="clear:both"></div>
		
		<div class="datatable">
		
			<? for($i=1;$i<=5;$i++){?>
			<div id="line<?=$i ?>" class="line line<?=$i ?>  <? if($i%2==0){ echo 'even'; }else{ echo 'odd'; } ?>  ">
				<div class="col col1  center "></div>
				<div class="col col2 "></div>
				<div class="col col3 "></div>
				<div class="col col4  center"></div>
				<div class="col col5  center "></div>
				<div class="col col6  center"></div>
				<div class="col col7  center "></div>
				<div style="clear:both"></div>
			</div>
			<? } ?>
			
		</div>
		<div id="pagging" style="display:none;"></div><div id="clock"  style="display:none;"></div>
	</body>
</html>