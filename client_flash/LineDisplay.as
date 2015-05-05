package  {
	
	import flash.display.MovieClip;
	import flash.text.TextField;
	
	
	public class LineDisplay extends MovieClip {
		
		
		public function LineDisplay() {
			// constructor code
		}
		
		public function clearData():void{
			(this.txtField1 as TextField).text = "";
			(this.txtField2 as TextField).text = "";
			(this.txtField3 as TextField).text = "";
			
			(this.txtField4 as TextField).text = "";
			(this.txtField5 as TextField).text = "";
			(this.txtField6 as TextField).text = "";
			
			(this.txtField7 as TextField).text = "";
		}
		public function loadData(d:Object):void{
			
			try{
				
				
				(this.txtField1 as TextField).text = trim(d.field_1);
				(this.txtField2 as TextField).text = trim(d.field_2);
				(this.txtField3 as TextField).text = trim(d.field_3);
				(this.txtField4 as TextField).text = trim(d.field_4);
				(this.txtField5 as TextField).text = trim(d.field_5);
				(this.txtField6 as TextField).text = trim(d.field_6);
				
				if(d.field_7.toString() == 'เสร็จการพิจารณาคดี') 
					(this.txtField7 as TextField).textColor = 0xFF0000;
				else 
					(this.txtField7 as TextField).textColor = 0xFFE9A5;
				(this.txtField7 as TextField).text = d.field_7;
			}catch(err){}
		}
		
		private function trim(obj:Object):String {
			return obj.toString().replace(/^\s+|\s+$/g, '');
		}
	}
	
}
