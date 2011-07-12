/*
 * ------------------------------------------------------------------------
 * P22Eventos - Version 1.0
 * ------------------------------------------------------------------------
 * Copyright (C) 2011
 * Author: Porta22 - Hugo Seabra
 * E-mail: hugo@porta22.com.br
 * Websites: http://porta22.com.br
 * This file may not be redistributed in whole or significant part.
 * Created on : 05/07/2011, 23:40:08
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * ------------------------------------------------------------------------
 */
var JFormValidator = new Class({
	initialize: function()
	{
		// Initialize variables
		this.handlers	= Object();
		this.custom		= Object();

		// Default handlers
		this.setHandler('username',
			function (value) {
				regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
				return !regex.test(value);
			}
		);

		this.setHandler('password',
			function (value) {
				regex=/^\S[\S ]{2,98}\S$/;
				return regex.test(value);
			}
		);

		this.setHandler('numeric',
			function (value) {
				regex=/^(\d|-)?(\d|,)*\.?\d*$/;
				return regex.test(value);
			}
		);

		this.setHandler('email',
			function (value) {
				regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
				return regex.test(value);
			}
		);

		this.setHandler('radio',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
					var options = par.parentNode.getElementsByTagName('input');

					var returnV = false;
					for (i=0, nl = options; i<nl.length; i++)
					{
						if (nl[i].checked == true)
							returnV = true;
					}
					return returnV;
				}
			}
		);

		this.setHandler('checkbox',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox2',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 2 options
				   if(count == 2){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox3',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 3 options
				   if(count == 3){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox4',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 4 options
				   if(count == 4){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox5',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 5 options
				   if(count == 5){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox6',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 6 options
				   if(count == 6){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox7',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 7 options
				   if(count == 7){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox8',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 8 options
				   if(count == 8){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox9',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 9 options
				   if(count == 9){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('checkbox10',
			function (par) {
				var nl, i;
				if(par.parentNode == null){
				   return true;
				}else{
				   var options = par.parentNode.getElementsByTagName('input');

				   var count = 0;
				   for (i=0, nl = options; i<nl.length; i++) {
					if (nl[i].checked)
						count++;
				   }

				   //exactly 10 options
				   if(count == 10){
				   	return true;
				   }

				   return false;
				}
			}
		);

		this.setHandler('dropdown',
			function (value) {
				if(value == -1){
				   return false;
				}else{
				   return true;
				}
			}
		);

		// Attach to forms with class 'form-validate'
		var forms = $$('form.form-validate');
		forms.each(function(form){ this.attachToForm(form); }, this);
	},

	setHandler: function(name, fn, en)
	{
		en = (en == '') ? true : en;
		this.handlers[name] = { enabled: en, exec: fn };
	},

	attachToForm: function(form)
	{
		// Iterate through the form object and attach the validate method to all input fields.
		$A(form.elements).each(function(el){
			el = $(el);
			if ((el.getTag() == 'input' || el.getTag() == 'button') && el.getProperty('type') == 'submit') {
				if (el.hasClass('validate')) {
					el.onclick = function(){return document.formvalidator.isValid(this.form);};
				}
			} else {
				el.addEvent('blur', function(){return document.formvalidator.validate(this);});
			}
		});
	},

	validate: function(el)
	{
		// If the field is required make sure it has a value
		if(el.getProperty('type') == "radio" || el.getProperty('type') == "checkbox"){
		   // radio and checkbox can have blank value
		}else{
		   if ($(el).hasClass('required')) {
		  	if (!($(el).getValue())) {
				this.handleResponse(false, el);
				return false;
			}
		   }
		}


		// Only validate the field if the validate class is set
		var handler = (el.className && el.className.search(/validate-([a-zA-Z0-9\_\-]+)/) != -1) ? el.className.match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
		if (handler == '') {
			this.handleResponse(true, el);
			return true;
		}


		// Check the additional validation types
		// Individual radio & checkbox can have blank value, providing one element in group is set

		if ((handler) && (handler != 'none') && (this.handlers[handler]) && $(el).getValue()) {
			// Execute the validation handler and return result
			if (this.handlers[handler].exec($(el).getValue()) != true) {
				this.handleResponse(false, el);
				return false;
			}

		}else if ((handler) && (handler != 'none') && (this.handlers[handler])) {
			if(el.getProperty('type') == "radio" || el.getProperty('type') == "checkbox"){
			   if (this.handlers[handler].exec(el.parentNode) != true) {
				this.handleResponse(false, el);
				return false;
			   }
			}
		}

		// Return validation state
		this.handleResponse(true, el);
		return true;
	},

	isValid: function(form)
	{
		var valid = true;

		// Validate form fields
		for (var i=0;i < form.elements.length; i++) {
			if (this.validate(form.elements[i]) == false) {
				valid = false;
			}
		}

		// Run custom form validators if present
		$A(this.custom).each(function(validator){
			if (validator.exec() != true) {
				valid = false;
			}
		});

		return valid;
	},

	handleResponse: function(state, el)
	{
		// Find the label object for the given field if it exists
		if (!(el.labelref)) {
			var labels = $$('label');
			labels.each(function(label){
				if (label.getProperty('for') == el.getProperty('id')) {
					el.labelref = label;
				}
			});
		}

		// Set the element and its label (if exists) invalid state
		if (state == false) {
			el.addClass('invalid');
			if (el.labelref) {
				$(el.labelref).addClass('invalid');
			}
		} else {
			el.removeClass('invalid');
			if (el.labelref) {
				$(el.labelref).removeClass('invalid');
			}
		}
	}
});

document.formvalidator = null;
Window.onDomReady(function(){
	document.formvalidator = new JFormValidator();
});