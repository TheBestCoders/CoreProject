
var coremenu={

//Specify full URL to down and right arrow images (23 is padding-right added to top level LIs with drop downs):
arrowimages: {down:['downarrowclass', base_url+'media/images/admin/down.gif', 23], right:['rightarrowclass', base_url+'media/images/admin/right.gif']},
transition: {overtime:300, outtime:300}, //duration of slide in/ out animation, in milliseconds
shadow: {enable:true, offsetx:5, offsety:5}, //enable shadow?
showhidedelay: {showdelay: 100, hidedelay: 200}, //set delay in milliseconds before sub menus appear and disappear, respectively

///////Stop configuring beyond here///////////////////////////

detectwebkit: navigator.userAgent.toLowerCase().indexOf("applewebkit")!=-1, //detect WebKit browsers (Safari, Chrome etc)
detectie6: document.all && !window.XMLHttpRequest,

getajaxmenu:function($, setting){ //function to fetch external page containing the panel DIVs
	var $menucontainer=$('#'+setting.contentsource[0]) //reference empty div on page that will hold menu
	$menucontainer.html("Loading Menu...")
	$.ajax({
		url: setting.contentsource[1], //path to external menu file
		async: true,
		error:function(ajaxrequest){
			$menucontainer.html('Error fetching content. Server Response: '+ajaxrequest.responseText)
		},
		success:function(content){
			$menucontainer.html(content)
			coremenu.buildmenu($, setting)
		}
	})
},


buildmenu:function($, setting){
	var c_menu=coremenu;
	var $mainmenu=$("#"+setting.mainmenuid+">ul") //reference main menu UL
	$mainmenu.parent().get(0).className=setting.classname || "coremenu"
	var $headers=$mainmenu.find("ul").parent()
	$headers.hover(
		function(e){
			$(this).children('a:eq(0)').addClass('selected')
		},
		function(e){
			$(this).children('a:eq(0)').removeClass('selected')
		}
	)
	$headers.each(function(i){ //loop through each LI header
		var $curobj=$(this).css({zIndex: 100-i}) //reference current LI header
		var $subul=$(this).find('ul:eq(0)').css({display:'block'})
		$subul.data('timers', {})
		this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
		this.istopheader=$curobj.parents("ul").length==1? true : false //is top level header?
		$subul.css({top:this.istopheader && setting.orientation!='v'? this._dimensions.h+"px" : 0})
		$curobj.children("a:eq(0)").css(this.istopheader? {paddingRight: c_menu.arrowimages.down[2]} : {}).append( //add arrow images
			'<img src="'+ (this.istopheader && setting.orientation!='v'? c_menu.arrowimages.down[1] : c_menu.arrowimages.right[1])
			+'" class="' + (this.istopheader && setting.orientation!='v'? c_menu.arrowimages.down[0] : c_menu.arrowimages.right[0])
			+ '" style="border:0;" />'
		)
		if (c_menu.shadow.enable){
			this._shadowoffset={x:(this.istopheader?$subul.offset().left+c_menu.shadow.offsetx : this._dimensions.w), y:(this.istopheader? $subul.offset().top+c_menu.shadow.offsety : $curobj.position().top)} //store this shadow's offsets
			if (this.istopheader)
				$parentshadow=$(document.body)
			else{
				var $parentLi=$curobj.parents("li:eq(0)")
				$parentshadow=$parentLi.get(0).$shadow
			}
			this.$shadow=$('<div class="ddshadow'+(this.istopheader? ' toplevelshadow' : '')+'"></div>').prependTo($parentshadow).css({left:this._shadowoffset.x+'px', top:this._shadowoffset.y+'px'})  //insert shadow DIV and set it to parent node for the next shadow div
		}
		$curobj.hover(
			function(e){
				var $targetul=$subul //reference UL to reveal
				var header=$curobj.get(0) //reference header LI as DOM object
				clearTimeout($targetul.data('timers').hidetimer)
				$targetul.data('timers').showtimer=setTimeout(function(){
					header._offsets={left:$curobj.offset().left, top:$curobj.offset().top}
					var menuleft=header.istopheader && setting.orientation!='v'? 0 : header._dimensions.w
					menuleft=(header._offsets.left+menuleft+header._dimensions.subulw>$(window).width())? (header.istopheader && setting.orientation!='v'? -header._dimensions.subulw+header._dimensions.w : -header._dimensions.w) : menuleft //calculate this sub menu's offsets from its parent
					if ($targetul.queue().length<=1){ //if 1 or less queued animations
						$targetul.css({left:menuleft+"px", width:header._dimensions.subulw+'px'}).animate({height:'show',opacity:'show'}, coremenu.transition.overtime)
						if (c_menu.shadow.enable){
							var shadowleft=header.istopheader? $targetul.offset().left+coremenu.shadow.offsetx : menuleft
							var shadowtop=header.istopheader?$targetul.offset().top+c_menu.shadow.offsety : header._shadowoffset.y
							if (!header.istopheader && coremenu.detectwebkit){ //in WebKit browsers, restore shadow's opacity to full
								header.$shadow.css({opacity:1})
							}
							header.$shadow.css({overflow:'', width:header._dimensions.subulw+'px', left:shadowleft+'px', top:shadowtop+'px'}).animate({height:header._dimensions.subulh+'px'}, coremenu.transition.overtime)
						}
					}
				}, coremenu.showhidedelay.showdelay)
			},
			function(e){
				var $targetul=$subul
				var header=$curobj.get(0)
				clearTimeout($targetul.data('timers').showtimer)
				$targetul.data('timers').hidetimer=setTimeout(function(){
					$targetul.animate({height:'hide', opacity:'hide'}, coremenu.transition.outtime)
					if (c_menu.shadow.enable){
						if (coremenu.detectwebkit){ //in WebKit browsers, set first child shadow's opacity to 0, as "overflow:hidden" doesn't work in them
							header.$shadow.children('div:eq(0)').css({opacity:0})
						}
						header.$shadow.css({overflow:'hidden'}).animate({height:0}, coremenu.transition.outtime)
					}
				}, coremenu.showhidedelay.hidedelay)
			}
		) //end hover
	}) //end $headers.each()
	$mainmenu.find("ul").css({display:'none', visibility:'visible'})
},

init:function(setting){
	if (typeof setting.customtheme=="object" && setting.customtheme.length==2){ //override default menu colors (default/hover) with custom set?
		var mainmenuid='#'+setting.mainmenuid
		var mainselector=(setting.orientation=="v")? mainmenuid : mainmenuid+', '+mainmenuid
		document.write('<style type="text/css">\n'
			+mainselector+' ul li a {background:'+setting.customtheme[0]+';}\n'
			+mainmenuid+' ul li a:hover {background:'+setting.customtheme[1]+';}\n'
		+'</style>')
	}
	this.shadow.enable=(document.all && !window.XMLHttpRequest)? false : this.shadow.enable //in IE6, always disable shadow
	jQuery(document).ready(function($){ //ajax menu?
		if (typeof setting.contentsource=="object"){ //if external ajax menu
			coremenu.getajaxmenu($, setting)
		}
		else{ //else if markup menu
			coremenu.buildmenu($, setting)
		}
	})
}

} //end coremenu variable

$(document).ready(function(e) {
	coremenu.init({
		mainmenuid: "coremenuid", //menu DIV id
		orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
		classname: 'coremenu', //class added to menu's outer DIV
		//customtheme: ["#1c5a80", "#18374a"],
		contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
	})
});