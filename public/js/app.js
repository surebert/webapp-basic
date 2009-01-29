/*
* @Author: Paul Visco
* @Version: 1.0 4/17/2008
*/

var app = {
	body : $('html'),
	
	/**
	@Name: app.warn
	@Description: All user warning feedback goes through this function.  
	@todo: Right now it alerts but can be fleshed out to a more extensive alert system in the future.
	*/
	warn : function(message){
		alert(message);
	},
	
	/**
	@Name: app.handleEvents
	@Description: Handles all events for the app module as passed on from the delegateEvents method
	*/
	handleEvents : {
		
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
	@Name: app.delegateEvents
	@Description: Delgates all the events for the system to the various other modules which may be loaded.  This prevents having to add many duplicate handlers
	*/
	delegateEvents : function(e){
		
		if(app.handleEvents[e.type]){
			app.handleEvents[e.type](e);
		}
		
	},
	
	/**
	@Name: app.addEvents
	@Description: used to delgate events for all modules based on user/mouse input
	*/
	addEvents : function(){
		
		this.body.events({
			click : app.delegateEvents,
			keyup : app.delegateEvents,
			dblclick : app.delegateEvents,
			mouseover : app.delegateEvents
		});
	
	},
	
	/**
	@Name: app.init
	@Description: Initializes the application and initializes additional modules if they are included.
	*/
	init : function(){
	
		this.addEvents();
		
	}
};

app.init();