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

var divs = [];
showThem = function (div, id, koncentr) {
	var descbox = $('desc_box_id');
		div		= $(div);

	if (!div) return;

	divs.include(div);
	if (div.getStyle('display') == 'none') {
		div.setStyles({
			display: 'block',
			opacity: 0
		})
	}
	if (div.status == 'show') {
		if ( descbox.value == id )
		{
			div.status = 'hide';
			descbox.value = '';

			var myShow = new Fx.Style(div, 'opacity', {
				duration: 400
			});
			myShow.stop();
			myShow.start(div.getStyle('opacity'), 0)
		}
	} else {
		divs.each(function (prva) {
			if (prva != div && prva.status == 'show') {
				prva.status = 'hide';
				var myShow = new Fx.Style(prva, 'opacity', {
					duration: 400
				});
				myShow.stop();
				myShow.start(prva.getStyle('opacity'), 0)
			}
		},
		this);
		div.status = 'show';
		var myShow = new Fx.Style(div, 'opacity', {
			onComplete: function () {
				if ($(koncentr)) $(koncentr).focus()
			}
		});
		myShow.stop();
		myShow.start(div.getStyle('opacity'), 1)
	}
	descbox.value = id;
}