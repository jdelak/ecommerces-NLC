/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/
(function(a){a.fn.fitVids=function(b){var e={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){var d=document.head||document.getElementsByTagName("head")[0];var c=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}";var f=document.createElement("div");f.innerHTML='<p>x</p><style id="fit-vids-style">'+c+"</style>";d.appendChild(f.childNodes[1])}if(b){a.extend(e,b)}return this.each(function(){var g=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];if(e.customSelector){g.push(e.customSelector)}var h=".fitvidsignore";if(e.ignore){h=h+", "+e.ignore}var i=a(this).find(g.join(","));i=i.not("object object");i=i.not(h);i.each(function(m){var o=a(this);if(o.parents(h).length>0){return}if(this.tagName.toLowerCase()==="embed"&&o.parent("object").length||o.parent(".fluid-width-video-wrapper").length){return}if((!o.css("height")&&!o.css("width"))&&(isNaN(o.attr("height"))||isNaN(o.attr("width")))){o.attr("height",9);o.attr("width",16)}var j=(this.tagName.toLowerCase()==="object"||(o.attr("height")&&!isNaN(parseInt(o.attr("height"),10))))?parseInt(o.attr("height"),10):o.height(),k=!isNaN(parseInt(o.attr("width"),10))?parseInt(o.attr("width"),10):o.width(),l=j/k;if(!o.attr("id")){var n="fitvid"+m;o.attr("id",n)}o.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",(l*100)+"%");o.removeAttr("height").removeAttr("width")})})}})(window.jQuery||window.Zepto);
