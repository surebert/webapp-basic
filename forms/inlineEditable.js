sb.forms.inlineEditable = {};

/**
@Name: sb.forms.inlineEditable.textarea
@Description: Creates a click to edit text block
@Param: Object
onBeforeEdit function Fires before editing begins,  Can be used to load raw text wihtout HTML from server
onSave function Fires after save, send back to server, stop editing
className string the classname for the widget, defaults to

@Example:
//use this in a doubleclick event for the text you want to make editable
//I assign it to the target so, you don't make extra editors

var target = e.target;

//var document_id = 'something from target';

if(!target.editor){
target.editor = new sb.forms.inlineEditable.textarea({
	onBeforeEdit : function(){
		var self = this;
		this.setValue('loading...');
		var aj = new sb.ajax({
			url : '/url/rawtext',
			data : {
				document_id : document_id
			},
			onResponse : function(raw_desc){
				self.setValue(raw_desc);
			}
		}).fetch();
	},
	onSave : function(value){
		if(value != 'loading...'){
			var aj = new sb.ajax({
				url : '/url/save',
				data : {
					document_id : document_id,
					desc : value
				},
				onResponse : function(r){
					target.innerHTML = r;
				}
			}).fetch();
		}
	}

});
}

target.editor.edit(target);

.sb_inlineEditable textarea{
	cursor:text;
	display:block;
	width:97%;
	height:100px;
	overflow:auto;
	font-family:tahoma;
	font-size:1.1em;
}

.sb_inlineEditable editbar{
	text-align:right;
}

.sb_inlineEditable button{
	background-color:#d88713;
	color:#7c4e0d;
}

.sb_inlineEditable button:hover{
	background-color:#e2b370;
}

*/
sb.forms.inlineEditable.textarea = function(params){

	this.onBeforeEdit = params.onBeforeEdit || this.onBeforeEdit;
	this.onSave = params.onSave || this.onSave;
	this.className = params.className || 'sb_inlineEditable';
	
	this.create();
	this.textarea.title = params.title || '';

};

sb.forms.inlineEditable.textarea.prototype = {

	/**
	@Name: sb.forms.inlineEditable.onBeforeEdit
	@Description: Fires before editing begins.  Can be used to load raw data with ajax
	to fill the textarea with.  Reference the textarea with this.textarea
	*/
	onBeforeEdit : function(){
		this.setValue(this.node.innerHTML);
	},
	/**
	@Name: sb.forms.inlineEditable.onBeforeEdit
	@Description: Passes the value of the textarea for you to save back with ajax
	@param string save The value of the textarea when save if fired
	*/
	onSave : function(value){},

	/**
	@Name: sb.forms.inlineEditable.edit
	@Description: Put the editor in edit mode
	@param: element el the element to edit
	*/
	edit : function(el){

		this.editor.title = 'shortcuts: esc to cancel, ctrl+s to save';
		
		this.element = $(el);

		this.editor.replace(this.element);
		if(typeof this.onBeforeEdit == 'function'){
			this.onBeforeEdit.call(this);
		}
	},

	/**
	@Name: sb.forms.inlineEditable.editStop
	@Description: Exit edit mode
	*/
	editStop : function(){
		this.element.replace(this.editor);
		this._origValue = '';
	},

	/**
	@Name: sb.forms.inlineEditable.setValue
	@Description: Sets the value of the textarea, can be used in onBeforeEdit
	*/
	setValue : function(value){
		this.textarea.value = value;
		this.focus();
	},

	/**
	@Name: sb.forms.inlineEditable.focus
	@Description: Focuses on text area and puts cursor at top left. automatically fires after setValue
	*/
	focus : function(){
		var ta = this.textarea;

		var range;
		if (this.textarea.setSelectionRange) {
			this.textarea.setSelectionRange(0, 0);
		} else {
			range = this.textarea.createTextRange();
			range.collapse(true);
			range.moveStart("character", 0);
			range.moveEnd("character", 0 - 0);
			range.select();
		}
		this.textarea.focus();

	},

	/**
	@Name: sb.forms.inlineEditable.editStop
	@Description: Used internally Creates editor
	*/
	create : function(){
		var self = this;
		if(!this.editor){
			this.editor = new sb.element({
				tag : 'div',
				className : self.className
			});

			this.textarea = new sb.element({
				tag : 'textarea',
				value : this.value,
				className : this.className,
				events : {
					keydown : function(e){
						if(!self._origValue){
							self._origValue = self.textarea.value;
						}
						
						if(e.keyCode == 27){
							self.editStop();
						} else if((e.ctrlKey || e.metaKey) && e.keyCode == 83){

							e.stopPropagation();
							e.preventDefault();
							self.onSave.call(self, self.textarea.value);
							self.editStop();
						}
					},
					blur : function(){
						if(!self._origValue || self._origValue == self.textarea.value){
							self.editStop();

						}

					}
				}
			});

			this.textarea.appendTo(this.editor);

			this.editBar = new sb.element({
				tag : 'editbar',
				styles : {
					display : 'block'
				},
				innerHTML : '<button>cancel</button> <button>save</button>',
				events : {
					mousedown : function(e){
						var target = e.target;
						if(target.innerHTML == 'save'){
							self.onSave.call(self, self.textarea.value);
						}
						self.editStop();

					}
				}
			});

			this.editBar.appendTo(this.editor);
		}
	}
};