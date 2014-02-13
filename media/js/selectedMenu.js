/**
 *
 * Selected Menu
 * Author: Alauddin Ansari (wonder_a7@yahoo.co.in)
 * December, 2011
 * Free License
 * 
 usage ->  $("selector").ColorPicker({showColorBox:boolean, selectedColor:'#000000', onSelect:function(returnColor){...}});
 *
 */

(function($) {
	
	$.fn.selectedMenu = function(options){
		
		var settings = {
			activeClass : 'selected',
			baseSelector : false
		};
		var menuLeft = 0;
		
		this.each(function(){
			if(options){
				$.extend(settings, options);
			}
			if(!$(this).data('selectedMenu')){
				$(this).data('selectedMenu', true);
				contructMenu($(this));
			}
			
		});
		
		function contructMenu(ele){
			if(!ele.is('ul')){
				ele = ele.find('ul:eq(0)');
			}
			if(settings.baseSelector)
				ele.append('<div class="selector" id="menuSelector" style="position:absolute;">&nbsp;</div>');
			
			var menu = ele.find('a');
			menuLeft = ele.offset().left;
			
			var path = location.pathname.substring(1);
			var selected = 0;
			menu.each(function(){
				var ids = $(this).attr('href').replace('http://','').replace('www.','');
				if(ids && ids!='#'){
					var id = ids.substring(0, ids.lastIndexOf('.'));
					$(this).attr('rel', id);
					
					if(path){
						if(path.indexOf(id) > -1){
							menu.removeClass(settings.activeClass);
							$(this).addClass(settings.activeClass);
							if(settings.baseSelector)
								setSelected(ele);
							selected++;
						}
					}
				}
			});
			
			if(selected==0){
				menu.eq(0).addClass(settings.activeClass);
				if(settings.baseSelector)
					setSelected(ele);
			}
			
			if(settings.baseSelector){
				ele.children().hover(function(){
					var thisLeft = $(this).offset().left;
					var leftPos = Math.round(thisLeft - menuLeft);
					$('#menuSelector').stop().animate({'left':leftPos+16, 'width':$(this).width()});
				}, function(){ });
				ele.hover(function(){ }, function(){
					setSelected(ele);
				});
			}
			
		}
		
		function setSelected(ele){
			var $selected = ele.find('a.selected');
			var selLeft = $selected.offset().left;
			selLeft = selLeft - menuLeft;
			$('#menuSelector').stop().animate({'left':selLeft+16, 'width':$selected.parent().width()});
		}
				
	}
	
})( jQuery );