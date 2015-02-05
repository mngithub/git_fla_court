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
			(this.txtField8 as TextField).text = "";
		}
		public function loadData(d:Object):void{
			(this.txtField1 as TextField).text = d.field_1;
			(this.txtField2 as TextField).text = d.field_2;
			(this.txtField3 as TextField).text = d.field_3;
			(this.txtField4 as TextField).text = d.field_4;
			(this.txtField5 as TextField).text = d.field_5;
			(this.txtField6 as TextField).text = d.field_6;
			(this.txtField7 as TextField).text = d.field_7;
			(this.txtField8 as TextField).text = d.field_8;
		}
	}
	
}
