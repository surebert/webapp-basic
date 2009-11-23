/*
* @Author: Paul Visco
* @Version: 1.1 11/23/2009
*/

var app = {

	/**
	@Name: app.warn
	@Description: All user warning feedback goes through this function.  
	@todo: Right now it alerts but can be fleshed out to a more extensive alert system in the future.
	*/
	warn : function(message){
		alert(message);
	},
	
	/**
	@Name: app.events
	@Description: Handles all events for the app module as passed on from the delegateEvents method
	*/
	events : {
		
		click : function(e){
			var target = e.target;
			alert("You clicked a "+target.nodeName+" node");
			
			switch(target.nodeName){
				
				case 'A':
				
					break;
			}
		},
		dblclick : function(e){
			
		},
		keyup : function(e){
			
		}
	},
	/**
	@Name: app.init
	@Description: Initializes the application and initializes additional modules if they are included.
	*/
	init : function(){
		
		sb.events.observer.observe(this);
		
	}
};

app.init();