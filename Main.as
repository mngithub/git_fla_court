package  {
	
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.net.URLLoader;
	import flash.display.StageScaleMode;
	import flash.display.StageAlign;
	import flash.system.Security;
	import flash.events.IOErrorEvent;
	import flash.net.URLRequest;
	import flash.utils.setInterval;
	import flash.utils.clearTimeout;
	import flash.utils.setTimeout;
	import flash.display.StageDisplayState;
	import flash.events.MouseEvent;
	import flash.text.TextField;
	import com.adobe.protocols.dict.events.DisconnectedEvent;
	
	
	public class Main extends MovieClip {
		
		public static var DEBUG_TRACE:Boolean = true;
		public static var rt:Main;
		
		// ---------------------------------------------------------------
		
		public static var CONFIG_XML:String = "app.xml";
		
		// ค่าที่อ่านได้จาก config file
		public static var CONFIG_AUTH:String;
		public static var CONFIG_SERVER_URL:String;
		public static var CONFIG_SERVER_URL_2:String;
		public static var CONFIG_STEP_QUERY:Number;
		public static var CONFIG_STEP_REFRESH_UI:Number;
		// ---------------------------------------------------------------

		private var clockIntervalID:uint;
		
		// นับ step ละ 1s
		private var stepIntervalID:uint;
		private var stepCnt:Number;
		
		private var page:Number;
		private var displayArray:Array;
		
		public function Main() {
			
			// เก็บ global reference
			Main.rt 					= this;
			this.stepCnt 				= 0; 
			this.modalPanel.visible 	= false;
			this.page					= 1;
			this.clearUI();
		
			try{
				this.stage.scaleMode 	= StageScaleMode.EXACT_FIT;
				this.stage.align 		= StageAlign.TOP;
				Security.exactSettings 	= false;
			}catch(err:Error){}
			
			// -------------------------------------------------------------------
			// -------------------------------------------------------------------
			// อ่านค่า config
			var loader:URLLoader = new URLLoader();
			loader.addEventListener(Event.COMPLETE, function(e:Event) {
				
				// อ่านค่า config.xml เรียบร้อยแล้ว
				var responseXML:XML = new XML(e.target.data);
				trace("--------------------------------");
				trace("LOADED - config file");
				trace("--------------------------------");
				
				var config:XML = responseXML;
				if(config.auth.length() < 1
					|| config.serverURL.length() < 1
					|| config.serverURL2.length() < 1
					|| config.stepQuery.length() < 1
					|| config.stepRefreshUI.length() < 1
				){
					failedOnLoadConfig();
					return;
				}
				Main.CONFIG_AUTH 						= config.auth;
				Main.CONFIG_SERVER_URL 					= config.serverURL;
				Main.CONFIG_SERVER_URL_2 				= config.serverURL2;
				Main.CONFIG_STEP_QUERY 					= Utils.parse(config.stepQuery);
				Main.CONFIG_STEP_REFRESH_UI 			= Utils.parse(config.stepRefreshUI);
				
				
				Main.rt.stepIntervalID = setInterval(function(){
					
					
					var isQuery:Boolean = false;
					var isRefreshUI:Boolean = false;
					if(stepCnt % Main.CONFIG_STEP_QUERY == 0){
						trace(stepCnt, Main.CONFIG_STEP_QUERY);
						// query and ui
						isQuery = true;
						doQueryMysql();
						
					}
					if(stepCnt % Main.CONFIG_STEP_REFRESH_UI == 0 && !isQuery){
						
						// ui
						isRefreshUI = true;
						
						page++;	
						clearUI();				
						updateUI();
					}
					//trace("step:", stepCnt, " query:",isQuery, " ui:", isUpdateUI);
					
					stepCnt++;
					if(stepCnt == Main.CONFIG_STEP_REFRESH_UI * Main.CONFIG_STEP_QUERY) stepCnt = 0;
					
					
					//doQueryMysql();
				}, 1000);
				doQueryMysql();
				
				
				// นาฬิกา 
			 	Main.rt.clockIntervalID = setInterval(function(){ updateClockUI();}, (60 * 1000));
				updateClockUI();
			});
			loader.addEventListener(IOErrorEvent.IO_ERROR, function(e:Event) {
										   
				// อ่านค่า config.xml ไม่สำเร็จ (ปิดโปรแกรม)
				var responseXML:XML = new XML(e.target.data);
				trace("--------------------------------");
				trace("FAILED - config file");
				trace("--------------------------------");
				failedOnLoadConfig();
			});
			loader.load(new URLRequest("./" + Main.CONFIG_XML));
			
			try { stage.displayState=StageDisplayState.FULL_SCREEN; }catch(err:Error){}
			
			// -------------------------------------------------------------------
			// -------------------------------------------------------------------
			
			
		
			this.hiddenToggleButton.addEventListener(MouseEvent.CLICK, function(e:Event) {
				try { 
					if (stage.displayState == StageDisplayState.NORMAL) {
						stage.displayState=StageDisplayState.FULL_SCREEN;
					} else {
						stage.displayState=StageDisplayState.NORMAL;
					}
				}catch(err:Error){}
			});
			
			this.queryButtonMysql.addEventListener(MouseEvent.CLICK, function(e:Event) {
				doQueryMysql();
			});
			this.queryButtonOracle.addEventListener(MouseEvent.CLICK, function(e:Event) {
				//doQueryOracle();
			});
			this.queryButtonMsAccess.addEventListener(MouseEvent.CLICK, function(e:Event) {
				//doQueryMsAccess();
			});
			
			
			/*
			this.hiddenQueryButton.addEventListener(MouseEvent.CLICK, function(e:Event) {
				startQueryAndUpdateUI();
			});
			*/
		}
		private function doQueryMysql():void{
			
			if(Main.DEBUG_TRACE) trace("[Mysql]");
			
			var service = new HTTPService("m",function(arr){
				
				//if(Main.DEBUG_TRACE) trace("[Response Query and UI]", arr);
				
				Main.rt.displayArray = arr;
				
				clearUI();				
				updateUI();
			});
		}
		
		/*
		private function doQueryOracle():void{
			
			if(Main.DEBUG_TRACE) trace("[Oracle]");
			
			var service = new HTTPService("o",function(arr){
				
				if(Main.DEBUG_TRACE) trace("[Response Query and UI]", arr.length);
				clearUI();
				updateUI(arr);
			});
		}
		
		private function doQueryMicrosoftSQL():void{
			
			if(Main.DEBUG_TRACE) trace("[MicrosoftSQL]");
			
			var service = new HTTPService("ms",function(arr){
				
				if(Main.DEBUG_TRACE) trace("[Response Query and UI]", arr.length);
				clearUI();
				updateUI(arr);
			});
		}
		
		private function doQueryMsAccess():void{
			
			if(Main.DEBUG_TRACE) trace("[Microsoft Access]");
			
			var service = new HTTPService("msa",function(arr){
				trace(arr);
				if(Main.DEBUG_TRACE) trace("[Response Query and UI]", arr.length);
				clearUI();
				updateUI(arr);
			});
		}
		*/
		// -------------------------------------------------------------------
		// UI
		// -------------------------------------------------------------------
		
		private function updateUI():void{
			
			if(this.displayArray == null) return;
			
			var totalPage:Number = Math.ceil(this.displayArray.length / 5);
			if(totalPage == 0) totalPage = 1;
			if(page > totalPage) page = 1;
			
			
			//trace("page",  page,"/" ,totalPage);
			(Main.rt["txtPage"] as TextField).text = "หน้าที่ " + page + " / " + totalPage;
			
			try{
				
				if(this.displayArray.length >= ((page-1)*5 + 1)) (Main.rt["line1"] as LineDisplay).loadData(this.displayArray[(page-1)*5 + 0]);
				if(this.displayArray.length >= ((page-1)*5 + 2)) (Main.rt["line2"] as LineDisplay).loadData(this.displayArray[(page-1)*5 + 1]);
				if(this.displayArray.length >= ((page-1)*5 + 3)) (Main.rt["line3"] as LineDisplay).loadData(this.displayArray[(page-1)*5 + 2]);
				if(this.displayArray.length >= ((page-1)*5 + 4)) (Main.rt["line4"] as LineDisplay).loadData(this.displayArray[(page-1)*5 + 3]);
				if(this.displayArray.length >= ((page-1)*5 + 5)) (Main.rt["line5"] as LineDisplay).loadData(this.displayArray[(page-1)*5 + 4]);
				
			}catch(err:Error){ trace("Error", err); }
		}
		
		// อัพเดทนาฬิกา
		private function updateClockUI():void{
			
			// นาฬิกา
			if (getChildByName("clock") != null){ 
				((this["clock"] as MovieClip).clockLabel as TextField).text = Utils.timeString();
			}
			// วันที่
			if (getChildByName("dateLabel") != null) this["dateLabel"].text = Utils.thaiDateString();
		}
		private function clearUI():void{
			(Main.rt["txtPage"] as TextField).text = "";
			(Main.rt["line1"] as LineDisplay).clearData();
			(Main.rt["line2"] as LineDisplay).clearData();
			(Main.rt["line3"] as LineDisplay).clearData();
			(Main.rt["line4"] as LineDisplay).clearData();
			(Main.rt["line5"] as LineDisplay).clearData();
		}
		
		// -------------------------------------------------------------------
		// Event Handler
		// -------------------------------------------------------------------
		
		// โหลด config ไม่สำเร็จ บังคับ refresh ข้อมูล
		private function failedOnLoadConfig():void{
			var msg: ModalDialog = new ModalDialog("เกิดข้อผิดพลาดในการอ่านค่า "+ Main.CONFIG_XML +" \n กรุณาลองเปิดโปรแกรมใหม่อีกครั้ง");
			(new DialogManager(msg)).showDialog();
		}
		
		// -------------------------------------------------------------------
		// -------------------------------------------------------------------
	}
}
