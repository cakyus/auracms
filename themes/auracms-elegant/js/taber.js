/*
 * jQuery clueTip plugin
 * Version 0.9.8  (05/22/2008)
 * @requires jQuery v1.1.4+
 * @requires Dimensions plugin (for jQuery versions < 1.2.5)
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
;(function($) { 
/*
 * @name flyout
 * @type jQuery
 * @cat Plugins/tooltip
 * @return jQuery
 * @original cluetip author Karl Swedberg
 *
 */

  var $cluetip, $cluetipOuter, $cluetipArrows, $dropShadow, imgCount;
  var $cluetipTC, $cluetipTR, $cluetipTL, $cluetipHeading, $cluetipClose, $cluetipContent, $cluetipMC, $cluetipMR, $cluetipML, $cluetipBC, $cluetipBR, $cluetipBL, $cluetipBW;
  $.fn.cluetip = function(js, options) {
    if (typeof js == 'object') {
      options = js;
      js = null;
    }
    return this.each(function(index) {
      var $this = $(this);      
      
      // support metadata plugin (v1.0 and 2.0)
      var opts = $.extend(false, {}, $.fn.cluetip.defaults, options || {}, $.metadata ? $this.metadata() : $.meta ? $this.data() : {});

      // start out with no contents (for ajax activation)
      var cluetipContents = false;
      var cluezIndex = parseInt(opts.cluezIndex, 10)-1;
      var isActive = false, closeOnDelay = 0;

      // create the cluetip divs
      if (!$('#cluetip').length) {
        $cluetipTC = $('<div class="flyout-tc"></div>');
        $cluetipTR = $('<div class="flyout-tr"></div>').prepend($cluetipTC);
        $cluetipTL = $('<div class="flyout-tl"></div>').prepend($cluetipTR);
        
        $cluetipHeading = $('<div class="flyout-heading"></div>');
        $cluetipClose = $('<div class="flyout-close"><a href="#" /></div>');
        $cluetipContent = $('<div id="flyout-content" class="flyout-description"></div>');
        $cluetipMC = $('<div class="flyout-mc"></div>').prepend($cluetipContent).prepend($cluetipHeading).prepend($cluetipClose);
        $cluetipMR = $('<div class="flyout-mr"></div>').prepend($cluetipMC);
        $cluetipML = $('<div class="flyout-ml"></div>').prepend($cluetipMR);        
        
        $cluetipBC = $('<div class="flyout-bc"></div>');
        $cluetipBR = $('<div class="flyout-br"></div>').prepend($cluetipBC);
        $cluetipBL = $('<div class="flyout-bl"></div>').prepend($cluetipBR);    
        
		$cluetipBW = $('<div class="flyout-bwrap"></div>').prepend($cluetipBL).prepend($cluetipML);               
        
        $cluetipOuter = $('<div id="cluetip-outer"></div>').prepend($cluetipBW).prepend($cluetipTL);
        $cluetip = $('<div id="cluetip"></div>').css({zIndex: opts.cluezIndex})
        .append($cluetipOuter).append('<div id="cluetip-extra"></div>')[insertionType](insertionElement).hide();
        $('<div id="cluetip-waitimage"></div>').css({position: 'absolute', zIndex: cluezIndex-1})
        .insertBefore('#cluetip').hide();
        $cluetip.css({position: 'absolute', zIndex: cluezIndex});
        $cluetipOuter.css({position: 'relative', zIndex: cluezIndex+1});
        $cluetipArrows = $('<div id="cluetip-arrows" class="cluetip-arrows"></div>').css({zIndex: cluezIndex+1}).appendTo('#cluetip');
      }
      var dropShadowSteps = (opts.dropShadow) ? +opts.dropShadowSteps : 0;
      if (!$dropShadow) {
        $dropShadow = $([]);
        for (var i=0; i < dropShadowSteps; i++) {
          $dropShadow = $dropShadow.add($('<div></div>').css({zIndex: cluezIndex-i-1, opacity:.05, top: 1+i, left: 1+i}));
        };
        $dropShadow.css({position: 'absolute', backgroundColor: '#000'})
        .prependTo($cluetip);
      }
      var tipAttribute = $this.attr(opts.attribute), ctClass = opts.cluetipClass;
      if (!tipAttribute && !js) return true;
      // if hideLocal is set to true, on DOM ready hide the local content that will be displayed in the clueTip      
      if (opts.local && opts.hideLocal) { $(tipAttribute + ':first').hide(); }
      var tOffset = parseInt(opts.topOffset, 10), lOffset = parseInt(opts.leftOffset, 10);
      // vertical measurement variables
      var tipHeight, wHeight;
      var defHeight = isNaN(parseInt(opts.height, 10)) ? 'auto' : (/\D/g).test(opts.height) ? opts.height : opts.height + 'px';
      var sTop, linkTop, posY, tipY, mouseY, baseline;
      // horizontal measurement variables
      var tipInnerWidth = isNaN(parseInt(opts.width, 10)) ? 275 : parseInt(opts.width, 10);
      var tipWidth = tipInnerWidth + (parseInt($cluetip.css('paddingLeft'))||0) + (parseInt($cluetip.css('paddingRight'))||0) + dropShadowSteps;
      var linkWidth = this.offsetWidth;
      var linkLeft, posX, tipX, mouseX, winWidth;
            
      // parse the title
//      var tipParts;
      var tipTitle = (opts.attribute != 'title') ? $this.attr(opts.titleAttribute) : '';
/*      if (opts.splitTitle) {
        if(tipTitle == undefined) {tipTitle = '';}
        tipParts = tipTitle.split(opts.splitTitle);
        tipTitle = tipParts.shift();
      }*/
      var localContent;

/***************************************      
* ACTIVATION
****************************************/
    
//activate clueTip
    var activate = function(event) {
      if (!opts.onActivate($this)) {
        return false;
      }
      isActive = true;
      $cluetip.removeClass().css({width: tipInnerWidth});
      if (tipAttribute == $this.attr('href')) {
        $this.css('cursor', opts.cursor);
      }
      $this.attr('title','');
      if (opts.hoverClass) {
        $this.addClass(opts.hoverClass);
      }
      linkTop = posY = $this.offset().top;
      linkLeft = $this.offset().left;
      mouseX = event.pageX;
      mouseY = event.pageY;
      if ($this[0].tagName.toLowerCase() != 'area') {
        sTop = $(document).scrollTop();
        winWidth = $(window).width();
      }
// position clueTip horizontally
      if (opts.positionBy == 'fixed') {
        posX = linkWidth + linkLeft + lOffset;
        $cluetip.css({left: posX});
      } else {
        posX = (linkWidth > linkLeft && linkLeft > tipWidth)
          || linkLeft + linkWidth + tipWidth + lOffset > winWidth 
          ? linkLeft - tipWidth - lOffset 
          : linkWidth + linkLeft + lOffset;
        if ($this[0].tagName.toLowerCase() == 'area' || opts.positionBy == 'mouse' || linkWidth + tipWidth > winWidth) { // position by mouse
          if (mouseX + 20 + tipWidth > winWidth) {  
            $cluetip.addClass(' cluetip-' + ctClass);
            posX = (mouseX - tipWidth - lOffset) >= 0 ? mouseX - tipWidth - lOffset - parseInt($cluetip.css('marginLeft'),10) + parseInt($cluetipContent.css('marginRight'),10) :  mouseX - (tipWidth/2);
          } else {
            posX = mouseX + lOffset;
          }
        }
        var pY = posX < 0 ? event.pageY + tOffset : event.pageY;
        $cluetip.css({left: (posX > 0 && opts.positionBy != 'bottomTop') ? posX : (mouseX + (tipWidth/2) > winWidth) ? winWidth/2 - tipWidth/2 : Math.max(mouseX - (tipWidth/2),0)});
      }
        wHeight = $(window).height();

/***************************************
* load a string from cluetip method's first argument
***************************************/
      if (js) {
        $cluetipContent.html(js);
        cluetipShow(pY);
      }
/***************************************
* load external file via ajax          
***************************************/

      else if (!opts.local && !opts.extjs && tipAttribute.indexOf('#') != 0) {
        if (cluetipContents && opts.ajaxCache) {
          $cluetipContent.html(cluetipContents);
          cluetipShow(pY);
        }
        else {
          var ajaxSettings = opts.ajaxSettings;
          ajaxSettings.url = tipAttribute;
          ajaxSettings.beforeSend = function() {
            $cluetipContent.empty();
            if (opts.waitImage) {
              $('#cluetip-waitimage')
              .css({top: mouseY+20, left: mouseX+20})
              .show();
            }
          };
         ajaxSettings.error = function() {
            if (isActive) {
              $cluetipContent.html('<i>sorry, the contents could not be loaded</i>');
            }
          };
          ajaxSettings.success = function(data) {
            cluetipContents = opts.ajaxProcess(data);
            if (isActive) {
              $cluetipContent.html(cluetipContents);
            }
          };
          ajaxSettings.complete = function() {
        		if (imgCount && !$.browser.opera) {
        		  $cluetipContent.load(function() {
          				$('#cluetip-waitimage').hide();
          			  if (isActive) cluetipShow(pY);
          			}); 
        		} else {
      				$('#cluetip-waitimage').hide();
        		  if (isActive) cluetipShow(pY);    
        		}
          };
          $.ajax(ajaxSettings);
        }

/***************************************
* load an element from the same page
***************************************/
      } else if (opts.local){
        var $localContent = $(tipAttribute + ':first'); 
        var localCluetip = $localContent.html();	
        $cluetipContent.html(localCluetip);        
/*        var localCluetip = $.fn.wrapInner ? $localContent.wrapInner('<div></div>').children().clone(true) : $localContent.html();	
        $.fn.wrapInner ? $cluetipContent.empty().append(localCluetip) : $cluetipContent.html(localCluetip);*/
        cluetipShow(pY);

    /***************************************
* load an element from the same page after update in extjs
***************************************/
      } else if (opts.extjs){
    	var val = $(opts.extjsActiveClass+':first').attr('record');
    	var coll = auctionCatalogStore.query('lotId',val);        	
        $cluetipContent.html(renderCatalogFlyout(coll));  
        cluetipShow(pY);
      }
    };

// get dimensions and options for cluetip and prepare it to be shown
    var cluetipShow = function(bpY) {
      $cluetip.addClass('cluetip-' + ctClass);
      
/*      if (opts.truncate) { 
        var $truncloaded = $cluetipInner.text().slice(0,opts.truncate) + '...';
        $cluetipInner.html($truncloaded);
      }*/
      function doNothing() {}; //empty function
      tipTitle ? $cluetipHeading.show().html(tipTitle) : (opts.showTitle) ? $cluetipHeading.show().html('&nbsp;') : $cluetipHeading.hide();
      if (opts.sticky) {
        $cluetipClose.click(function() {
          cluetipClose();
          return false;
        });
        if (opts.mouseOutClose) {
          if ($.fn.hoverIntent && opts.hoverIntent) { 
            $cluetip.hoverIntent({
              over: doNothing, 
              timeout: opts.hoverIntent.timeout,  
              out: function() { $cluetipClose.trigger('click'); }
            });
          } else {
            $cluetip.hover(doNothing, 
            function() {$cluetipClose.trigger('click'); });
          }
        } else {
          $cluetip.unbind('mouseout');
        }
      }
// now that content is loaded, finish the positioning 
      var direction = '';
      $cluetipOuter.css({overflow: defHeight == 'auto' ? 'visible' : 'auto', height: defHeight});
      tipHeight = defHeight == 'auto' ? Math.max($cluetip.outerHeight(),$cluetip.height()) : parseInt(defHeight,10);   
      tipY = posY;
      baseline = sTop + wHeight;
      if (opts.positionBy == 'fixed') {
        tipY = posY - opts.dropShadowSteps + tOffset;
      } else if ( (posX < mouseX && Math.max(posX, 0) + tipWidth > mouseX) || opts.positionBy == 'bottomTop') {
        if (posY + tipHeight + tOffset > baseline && mouseY - sTop > tipHeight + tOffset) { 
          tipY = mouseY - tipHeight - tOffset;
          direction = 'top';
        } else { 
          tipY = mouseY + tOffset;
          direction = 'bottom';
        }
      } else if ( posY + tipHeight + tOffset > baseline ) {
        tipY = (tipHeight >= wHeight) ? sTop : baseline - tipHeight - tOffset;
      } else if ($this.css('display') == 'block' || $this[0].tagName.toLowerCase() == 'area' || opts.positionBy == "mouse") {
        tipY = bpY - tOffset;
      } else {
        tipY = posY - opts.dropShadowSteps;
      }
      if (direction == '') {
        posX < linkLeft ? direction = 'left' : direction = 'right';
      }
      $cluetip.css({top: tipY + 'px'}).removeClass().addClass('clue-' + direction + '-' + ctClass).addClass(' cluetip-' + ctClass);
		if (opts.arrows) 
		{ // set up arrow positioning to align with element
	        var bgY = (posY - tipY - opts.dropShadowSteps);
		
	        $cluetipArrows.css({top: (/(left|right)/.test(direction) && posX >=0 && bgY > 0) ? bgY + 'px' : /(left|right)/.test(direction) ? 0 : ''}).show();
	        if(opts.positionBy == 'bottomTop') {
		  $cluetipArrows.css({top: '-36px'});
		}
		var left_css = ' cluetip-arrows-left';
	        var right_css = ' cluetip-arrows-right';
	      	var bg_class = (/(left|right)/.test(direction)) ? 
	      									(direction == 'right'? right_css : left_css ) : '';
	      									
	      	if(bg_class == left_css){
	      		$cluetipArrows.removeClass(right_css);
	      	}else{
	      		$cluetipArrows.removeClass(left_css);
	      	}
	      	
	      	$cluetipArrows.addClass(bg_class);
	      
      	
      } else {
        $cluetipArrows.hide();
      }

// (first hide, then) ***SHOW THE CLUETIP***
      $dropShadow.hide();
      $cluetip.hide()[opts.fx.open](opts.fx.open != 'show' && opts.fx.openSpeed);
      if (opts.dropShadow) $dropShadow.css({height: tipHeight, width: tipInnerWidth}).show();
      if ($.fn.bgiframe) { $cluetip.bgiframe(); }
      // trigger the optional onShow function
      /*if (opts.delayedClose > 0) {
        closeOnDelay = setTimeout(cluetipClose, opts.delayedClose);
      }*/
      opts.onShow($cluetip, $cluetipContent);
      
    };

/***************************************
   =INACTIVATION
-------------------------------------- */
    var inactivate = function() {
      isActive = false;
      $('#cluetip-waitimage').hide();
      if (!opts.sticky || (/click|toggle/).test(opts.activation) ) {
        cluetipClose();
clearTimeout(closeOnDelay);        
      };
      if (opts.hoverClass) {
        $this.removeClass(opts.hoverClass);
      }
      $('.cluetip-clicked').removeClass('cluetip-clicked');
    };
// close cluetip and reset some things
    var cluetipClose = function() {
      /*$cluetipOuter 
      .parent().hide().removeClass().end()
      .children().empty();*/
      $cluetipOuter.parent().hide();
      $cluetipHeading.empty();
      $cluetipContent.empty();
      
      if (tipTitle) {
        $this.attr(opts.titleAttribute, tipTitle);
      }
      $this.css('cursor','');
      if (opts.arrows) $cluetipArrows.css({top: ''});
    };

/***************************************
   =BIND EVENTS
-------------------------------------- */
  // activate by click
      if ( (/click|toggle/).test(opts.activation) ) {
        $this.click(function(event) {
          if ($cluetip.is(':hidden') || !$this.is('.cluetip-clicked')) {
            activate(event);
            $('.cluetip-clicked').removeClass('cluetip-clicked');
            $this.addClass('cluetip-clicked');

          } else {
            inactivate(event);

          }
          this.blur();
          return false;
        });
  // activate by focus; inactivate by blur    
      } else if (opts.activation == 'focus') {
        $this.focus(function(event) {
          activate(event);
        });
        $this.blur(function(event) {
          inactivate(event);
        });
  // activate by hover
    // clicking is returned false if cluetip url is same as href url
      } else {
        $this.click(function() {
          if ($this.attr('href') && $this.attr('href') == tipAttribute && !opts.clickThrough) {
            return false;
          }
        });
        if ($.fn.hoverIntent && opts.hoverIntent) {
          $this.mouseover(function() {$this.attr('title',''); })
          .hoverIntent({
            sensitivity: opts.hoverIntent.sensitivity,
            interval: opts.hoverIntent.interval,  
            over: function(event) {
              activate(event);
              //mouseTracks(event);
            }, 
            timeout: opts.hoverIntent.timeout,  
            out: function(event) {inactivate(event); $this.unbind('mousemove');}
          });           
        } else {
          $this.hover(function(event) {
            activate(event);
            //mouseTracks(event);
          }, function(event) {
            inactivate(event);
            $this.unbind('mousemove');
          });
        }
      }
    });
  };
  
/*
 * options for clueTip
 */
  
  $.fn.cluetip.defaults = {  // set up default options
    width:            400,      // The width of the clueTip
    height:           'auto',   // The height of the clueTip
    cluezIndex:       97,       // Sets the z-index style property of the clueTip
    positionBy:       'auto',   // Sets the type of positioning: 'auto', 'mouse','bottomTop', 'fixed'
    topOffset:        15,       // Number of px to offset clueTip from top of invoking element
    leftOffset:       15,       // Number of px to offset clueTip from left of invoking element
    extjs:            false,    // same as local - used to update with extjs
    extjsActiveClass: 'a.flyout-active',	//the active class of the element that triggers the flyout
    local:            false,    // Whether to use content from the same page for the clueTip's body
    hideLocal:        true,     // If local option is set to true, this determines whether local content
                                // to be shown in clueTip should be hidden at its original location
    attribute:        'rel',    // the attribute to be used for fetching the clueTip's body content
    titleAttribute:   'title',  // the attribute to be used for fetching the clueTip's title    
    showTitle:        true,     // show title bar of the clueTip, even if title attribute not set
    cluetipClass:     'default',// class added to outermost clueTip div in the form of 'cluetip-' + clueTipClass.
    hoverClass:       '',       // class applied to the invoking element onmouseover and removed onmouseout
    waitImage:        true,     // whether to show a "loading" img, which is set in jquery.cluetip.css
    cursor:           'pointer',
    arrows:           true,    // if true, displays arrow on appropriate side of clueTip
    dropShadow:       true,     // set to false if you don't want the drop-shadow effect on the clueTip
    dropShadowSteps:  5,        // adjusts the size of the drop shadow
    sticky:           true,    // keep visible until manually closed
    mouseOutClose:    true,    // close when clueTip is moused out
    activation:       'hover',  // set to 'click' to force user to click to show clueTip
                                // set to 'focus' to show on focus of a form element and hide on blur
    clickThrough:     false,    // if true, and activation is not 'click', then clicking on link will take user to the link's href,
                                // even if href and tipAttribute are equal
    closePosition:    'title',    // location of close text for sticky cluetips; can only be set to 'title'

    // effect and speed for opening clueTips
    fx: {             
                      open:       'show', // can be 'show' or 'slideDown' or 'fadeIn'
                      openSpeed:  ''
    },     

    // settings for when hoverIntent plugin is used             
    hoverIntent: {    
                      sensitivity:  3,
              			  interval:     50,
              			  timeout:      0
    },

    // function to run just before clueTip is shown.           
    onActivate:       function(e) {return true;},

    // function to run just after clueTip is shown.
    onShow:           function(ct, c){},
    
    // whether to cache results of ajax request to avoid unnecessary hits to server    
    ajaxCache:        true,  

    // process data retrieved via xhr before it's displayed
    ajaxProcess:      function(data) {
                        data = data.replace(/<s(cript|tyle)(.|\s)*?\/s(cript|tyle)>/g, '').replace(/<(link|title)(.|\s)*?\/(link|title)>/g,'');
                        return data;
    },                

    // can pass in standard $.ajax() parameters, not including error, complete, success, and url
    ajaxSettings: {   
                      dataType: 'html'
    },
    debug: false
  };


/*
 * Global defaults for clueTips. Apply to all calls to the clueTip plugin.
 *
 * @example $.cluetip.setup({
 *   insertionType: 'prependTo',
 *   insertionElement: '#container'
 * });
 * 
 * @property
 * @name $.cluetip.setup
 * @type Map
 * @cat Plugins/tooltip
 * @option String insertionType: Default is 'appendTo'. Determines the method to be used for inserting the clueTip into the DOM. Permitted values are 'appendTo', 'prependTo', 'insertBefore', and 'insertAfter'
 * @option String insertionElement: Default is 'body'. Determines which element in the DOM the plugin will reference when inserting the clueTip.
 *
 */
   
  var insertionType = 'appendTo', insertionElement = 'body';
  $.cluetip = {};
  $.cluetip.setup = function(options) {
    if (options && options.insertionType && (options.insertionType).match(/appendTo|prependTo|insertBefore|insertAfter/)) {
      insertionType = options.insertionType;
    }
    if (options && options.insertionElement) {
      insertionElement = options.insertionElement;
    }
  };
  
})(jQuery);
/* Copyright (c) 2007 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.2
 * Requires jQuery 1.1.3+
 * Docs: http://docs.jquery.com/Plugins/livequery
 */
(function($){$.extend($.fn,{livequery:function(type,fn,fn2){var self=this,q;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&type==query.type&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid))return(q=query)&&false;});q=q||new $.livequery(this.selector,this.context,type,fn,fn2);q.stopped=false;$.livequery.run(q.id);return this;},expire:function(type,fn,fn2){var self=this;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&(!type||type==query.type)&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid)&&!this.stopped)$.livequery.stop(query.id);});return this;}});$.livequery=function(selector,context,type,fn,fn2){this.selector=selector;this.context=context||document;this.type=type;this.fn=fn;this.fn2=fn2;this.elements=[];this.stopped=false;this.id=$.livequery.queries.push(this)-1;fn.$lqguid=fn.$lqguid||$.livequery.guid++;if(fn2)fn2.$lqguid=fn2.$lqguid||$.livequery.guid++;return this;};$.livequery.prototype={stop:function(){var query=this;if(this.type)this.elements.unbind(this.type,this.fn);else if(this.fn2)this.elements.each(function(i,el){query.fn2.apply(el);});this.elements=[];this.stopped=true;},run:function(){if(this.stopped)return;var query=this;var oEls=this.elements,els=$(this.selector,this.context),nEls=els.not(oEls);this.elements=els;if(this.type){nEls.bind(this.type,this.fn);if(oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)$.event.remove(el,query.type,query.fn);});}else{nEls.each(function(){query.fn.apply(this);});if(this.fn2&&oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)query.fn2.apply(el);});}}};$.extend($.livequery,{guid:0,queries:[],queue:[],running:false,timeout:null,checkQueue:function(){if($.livequery.running&&$.livequery.queue.length){var length=$.livequery.queue.length;while(length--)$.livequery.queries[$.livequery.queue.shift()].run();}},pause:function(){$.livequery.running=false;},play:function(){$.livequery.running=true;$.livequery.run();},registerPlugin:function(){$.each(arguments,function(i,n){if(!$.fn[n])return;var old=$.fn[n];$.fn[n]=function(){var r=old.apply(this,arguments);$.livequery.run();return r;}});},run:function(id){if(id!=undefined){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id);}else
$.each($.livequery.queries,function(id){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id);});if($.livequery.timeout)clearTimeout($.livequery.timeout);$.livequery.timeout=setTimeout($.livequery.checkQueue,20);},stop:function(id){if(id!=undefined)$.livequery.queries[id].stop();else
$.each($.livequery.queries,function(id){$.livequery.queries[id].stop();});}});$.livequery.registerPlugin('append','prepend','after','before','wrap','attr','removeAttr','addClass','removeClass','toggleClass','empty','remove');$(function(){$.livequery.play();});var init=$.prototype.init;$.prototype.init=function(a,c){var r=init.apply(this,arguments);if(a&&a.selector)r.context=a.context,r.selector=a.selector;if(typeof a=='string')r.context=c||document,r.selector=a;return r;};$.prototype.init.prototype=$.prototype;})(jQuery);
/*
 * jQuery Color Animations
 * Copyright 2007 John Resig
 * Released under the MIT and GPL licenses.
 */

(function(jQuery){

	// We override the animation for all of these color styles
	jQuery.each(['backgroundColor', 'borderBottomColor', 'borderLeftColor', 'borderRightColor', 'borderTopColor', 'color', 'outlineColor'], function(i,attr){
		jQuery.fx.step[attr] = function(fx){
			if ( fx.state == 0 ) {
				fx.start = getColor( fx.elem, attr );
				fx.end = getRGB( fx.end );
			}

			fx.elem.style[attr] = "rgb(" + [
				Math.max(Math.min( parseInt((fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2]), 255), 0)
			].join(",") + ")";
		}
	});

	// Color Conversion functions from highlightFade
	// By Blair Mitchelmore
	// http://jquery.offput.ca/highlightFade/

	// Parse strings looking for color tuples [255,255,255]
	function getRGB(color) {
		var result;

		// Check if we're already dealing with an array of colors
		if ( color && color.constructor == Array && color.length == 3 )
			return color;

		// Look for rgb(num,num,num)
		if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color))
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];

		// Look for rgb(num%,num%,num%)
		if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color))
			return [parseFloat(result[1])*2.55, parseFloat(result[2])*2.55, parseFloat(result[3])*2.55];

		// Look for #a0b1c2
		if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color))
			return [parseInt(result[1],16), parseInt(result[2],16), parseInt(result[3],16)];

		// Look for #fff
		if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color))
			return [parseInt(result[1]+result[1],16), parseInt(result[2]+result[2],16), parseInt(result[3]+result[3],16)];

		// Otherwise, we're most likely dealing with a named color
		return colors[jQuery.trim(color).toLowerCase()];
	}
	
	function getColor(elem, attr) {
		var color;

		do {
			color = jQuery.curCSS(elem, attr);

			// Keep going until we find an element that has color, or we hit the body
			if ( color != '' && color != 'transparent' || jQuery.nodeName(elem, "body") )
				break; 

			attr = "backgroundColor";
		} while ( elem = elem.parentNode );

		return getRGB(color);
	};
	
	// Some named colors to work with
	// From Interface by Stefan Petre
	// http://interface.eyecon.ro/

	var colors = {
		aqua:[0,255,255],
		azure:[240,255,255],
		beige:[245,245,220],
		black:[0,0,0],
		blue:[0,0,255],
		brown:[165,42,42],
		cyan:[0,255,255],
		darkblue:[0,0,139],
		darkcyan:[0,139,139],
		darkgrey:[169,169,169],
		darkgreen:[0,100,0],
		darkkhaki:[189,183,107],
		darkmagenta:[139,0,139],
		darkolivegreen:[85,107,47],
		darkorange:[255,140,0],
		darkorchid:[153,50,204],
		darkred:[139,0,0],
		darksalmon:[233,150,122],
		darkviolet:[148,0,211],
		fuchsia:[255,0,255],
		gold:[255,215,0],
		green:[0,128,0],
		indigo:[75,0,130],
		khaki:[240,230,140],
		lightblue:[173,216,230],
		lightcyan:[224,255,255],
		lightgreen:[144,238,144],
		lightgrey:[211,211,211],
		lightpink:[255,182,193],
		lightyellow:[255,255,224],
		lime:[0,255,0],
		magenta:[255,0,255],
		maroon:[128,0,0],
		navy:[0,0,128],
		olive:[128,128,0],
		orange:[255,165,0],
		pink:[255,192,203],
		purple:[128,0,128],
		violet:[128,0,128],
		red:[255,0,0],
		silver:[192,192,192],
		white:[255,255,255],
		yellow:[255,255,0]
	};
	
})(jQuery);

/*
 *
 * @package	DT Spinner
 * @author	Koa Metter <koa@domaintools.com>
 * @version	1.0 <Aug 19, 2009>
 *
 * @uses /codebase/modules/_css/master_all.css  -  Required CSS style
 *
 * Creates a spinning DomainTools ajax-loader in a specified location.
 *
 * @param boolean toggle Choose whether to show (true) or hide (false) the spinner.
 *
 * @example
 * $("#div").dtSpinnerToggle();
 *
 */

(function($) {
	
	$.fn.dtSpinnerToggle = function(toggle) {
		
		// run for each call
		return this.each(function() {
			
			this.toggle = (typeof toggle !== "undefined" && toggle != "" && toggle == true) ? "block" : "none";
			
			var spinner = $(".ajax-loader");
			
			if (spinner.length > 0) {
				
				spinner.css("display",this.toggle);
			
			} else {
			
				$(this).append("<div class='ajax-loader'></div>");
				$(".ajax-loader").css("display",this.toggle);	
			}
			
		});
		
	}
	
})(jQuery);

/*
 *
 * @package	DT Carousel
 * @author	Koa Metter <koa@domaintools.com>
 * @copyright	DomainTools LLC
 * @version	1.0 <Dec 30, 2009>
 *
 * @uses jQuery 1.3+
 *
 * @param object
 *		@param string	className		Set custom class name (default: "dt-carousel")
 *		@param int		rotateDelay		Choose auto-rotation delay in milliseconds (default: 5000)
 *		@param bool		rotateOnce		Choose to stop rotation at the end or to continue back to beginning (default: false)
 *		@param int/null	goToPosition	Choose a position to go to and stop at once the end is reached (default: 0) - if you want
 * 										to stop at the end use the rotateOnce option to avoid re-loading contents
 *
 * @example
 * $("#div").dtCarousel({
 *		className: 		"my-carousel",
 *		rotateDelay: 	8000
 * });
 *
 */

(function($) {

	$.fn.dtCarousel = function(options)
	{

		/******************/
		/* Error Handling */
		/******************/

		// report error if $(this) is undefined
		if (typeof $(this).attr("class") === "undefined" && typeof $(this).attr("id") === "undefined") {
			//console.log("DT Carousel Error :: Unable to display rotator. The container '"+$(this).selector+"' does not exist.");
			return false;
		}

		/**********************/
		/* Defaults & Globals */
		/**********************/

		// options defaults
		var opts = $.extend({
			className:		"dt-carousel",
			rotateDelay: 	5000,
			rotateOnce:		false,
			goToPosition:	0
		}, options);

		// get literal value of $(this)
		var selector = $(this).selector;

		// create unique class name
		var className = $(this).attr("id").length > 0
				? $(this).attr("id") + "-" + opts.className
				: $(this).attr("class") + "-" + opts.className;

		/*******************/
		/* Execute Plugin! */
		/*******************/

		// run for each call
		return this.each(function() {

			// check if delay is an int
			if (!isInteger(opts.rotateDelay))
				return false;

			// set global vars
			$.carousel = {
				content: 	$(selector).children("div.dt-carousel:not('.dt-carousel-nav, span, a')"),
				selector:	selector,
				delay:		opts.rotateDelay,
				rotateOnce:	opts.rotateOnce,
				goToPosition: opts.goToPosition,
				navSpan: 	$(selector + " .dt-carousel-nav span"),
				timer:		setInterval(function(){ toggleNext(); }, opts.rotateDelay),
				pause:		0,
				forcePause:	0
			};

			// bind nav links
			$(selector + " .dt-carousel-link").each(function(){
				$(this).click(function(){
					page = $(this).text();
					togglePage(page);
					return false;
				});
			});

			// bind hover pauses
			$(selector).hover(
				function(){
					delayHandler(1);
				},
				function(){
					delayHandler(0);
				}
			);
		});


		/********************/
		/* Plugin Functions */
		/********************/

		/*
		 * Checks integers
		 * @return boolean
		 */
		function isInteger (s)
		{
			var i;

			if (isEmpty(s))
				if (isInteger.arguments.length == 1)
					return 0;
			else
				return (isInteger.arguments[1] == true);

			for (i = 0; i < s.length; i++)
			{
				var c = s.charAt(i);
				if (!isDigit(c))
					return false;
			}

			return true;
		}

		/*
		 * Checks if empty
		 * @return boolean
		 */
		function isEmpty (s) { return ((s == null) || (s.length == 0)) }

		/*
		 * Checks if digit
		 * @return boolean
		 */
		function isDigit (c) { return ((c >= "0") && (c <= "9")) }

	}


})(jQuery);

/*
 * Switches to next css selector
 */
function toggleNext ()
{
	content		= jQuery.carousel.content;
	selector	= jQuery.carousel.selector;
	delay		= jQuery.carousel.delay;
	navSpan		= jQuery.carousel.navSpan;
	rotateOnce	= jQuery.carousel.rotateOnce;
	goToPosition = jQuery.carousel.goToPosition;

	if (content.length <= 1)
		return false;

	visibleBlock = content.filter(":visible");

	// return to the beginning if we're at the end
	if (visibleBlock.next(".dt-carousel:not('.dt-carousel-nav, span, a')").length <= 0)
	{
		if(rotateOnce){	//stop at the end without re-loading contents
			if(goToPosition == 0){
				delayHandler(1);
				jQuery.carousel.forcePause = 1;
				return false;
			}
		}

		if(goToPosition != 0){	//go to desired position after reaching the end
			togglePage(goToPosition);
			return false;
		}

		visibleBlock.hide();

		// remove class from spans
		visibleSpan = navSpan.filter(".dt-carousel-current");
		visibleSpan.removeClass("dt-carousel-current");

		content.eq(0).fadeIn();
		navSpan.eq(0).addClass("dt-carousel-current");

	}
	else
	{
		visibleBlock.hide();

		// remove class from spans
		visibleSpan = navSpan.filter(".dt-carousel-current");
		visibleSpan.removeClass("dt-carousel-current");

		visibleBlock.next(".dt-carousel:not('.dt-carousel-nav, span, a')").fadeIn();
		visibleSpan.next().addClass('dt-carousel-current');
	}

	jQuery.carousel.pause = 0;
	delayHandler(0);
}

/*
 * Handles clearing/setting intervals
 */
function delayHandler (pause)
{
	paused = jQuery.carousel.pause;

	// we want to pause, and we are NOT currently paused
	if (pause && !paused) {
		jQuery.carousel.pause = 1;
		clearInterval(jQuery.carousel.timer);
	}

	// we're NOT wanting to pause, and we are currently paused
	// do it only if forcePause is false
	if (!jQuery.carousel.forcePause)
	{
		if (!pause && paused) {
			jQuery.carousel.pause = 0;
			jQuery.carousel.timer = setInterval(function(){ toggleNext(); }, jQuery.carousel.delay);
		}
	}

	return;
}

/*
 * Switches to specific css selector
 */
function togglePage (page)
{
	content		= jQuery.carousel.content;
	selector	= jQuery.carousel.selector;
	navSpan		= jQuery.carousel.navSpan;

	page = page - 1;

	if(content.eq(page).length !=0 && navSpan.eq(page).length !=0){

		visibleBlock = content.filter(":visible");
		visibleBlock.hide();

		// remove class from spans
		visibleSpan = navSpan.filter(".dt-carousel-current");
		visibleSpan.removeClass("dt-carousel-current");

		content.eq(page).fadeIn();
		navSpan.eq(page).addClass("dt-carousel-current");

	}

	delayHandler(1);
	jQuery.carousel.forcePause = 1;

	return;
}
/*******************************/
/*
 *
 * @package	DT Rounded Corners
 * @author	Emily Ziegler
 * @version	1.0 <December 2009>
 *
 * @param object
 *		@param string	borderColor		Set custom color class name (default: "grey", others: "blue-grey")
 *		@param string	fillColor		Set custom color class name (default: "white", others: "grey")
 *		@param string	containerId		Set the div id for the rounded corner container
 *		@param string	containerClass  Set an additional class for the rounded corner container
 *		@param string	headerThickness	Set the container header thickness (default: "", others: "thin" or "thick")
 *		@param string	headerTitle		Set the header title (headerThickness must be "thick" to display text)
 *		@param string	width			Set the width of the rounded corner container (default: "100%")
 *		@param bool		wrapContent		Set property to allow new rounded corner container to load outside or inside selector
 *		//only necessary for user messaging
 *		@param string	type			Set the user message container type (default:"error", others: "success" or "warning")
 *		@param bool	viewMultiple	Set property allowing multiple rounded message boxes (default:false)
 *
 *
 * @example for error messaging
 *	$(".page-level-message").dtRoundCorners(
		{
			type: "error",
			wrapContent: false,
		},
		[
			[
				"JS Message Summary",
				"JS Message Details"
			],
			[
				"+1 Message Details",
				"+2 Message Details",
				"+3 Message Details"
			]
		]
 *	);
 *
 * @example for rounded grey boxes
 * 	$("#box1").dtRoundCorners({
 		width:"200px",
		borderColor:"grey",
		fillColor:"grey"
 *	});
 *
 */

(function($) {

	$.fn.dtRoundCorners = function(options, messages) {

		/**********************/
		/* Defaults & Globals */
		/**********************/

		// options defaults
		var opts = $.extend({
			containerId:		"",
			containerClass:		"",
			//
			headerThickness:	"",				//possible options: "", "thin", or "thick"
			headerTitle:		"",				//corresponding headerThickness must be "thick" to display text in the header
			width:				"100%",
			borderColor:		"grey",			//possible options: "grey" ...
			fillColor:			"white",		//possible options: "grey", "white", ...
			//only necessary for user messaging
			wrapContent:		true,
			type:				"",				//possible options: "error", "success", or "warning"
			viewMultiple:		false
		}, options);

		if(opts.type == "")
		{
			opts.wrapContent = true; //for now, cannot be true for anything but user messages

			// if opts.headerThickness is not one of the following, default to ""
			if (opts.headerThickness != "" && opts.headerThickness != "thin" && opts.headerThickness != "thick") {
				opts.headerThickness = "";
			}

			// if opts.borderColor is not one of the following, default to "grey"
			if (opts.borderColor != "grey" && opts.borderColor != "blue-grey") {
				opts.borderColor = "grey";
			}

			// if opts.borderColor is not one of the following, default to "grey"
			if (opts.fillColor != "grey" && opts.fillColor != "white") {
				opts.fillColor = "white";
			}
		} else {
			opts.headerThickness = "thin";
			opts.fillColor = "";

			//defaults to error type
			if(opts.type == "error" || opts.type == "warning" || opts.type == "success")
				opts.borderColor = opts.type;
			else
				opts.borderColor = "error";

		}

		/*******************/
		/* Execute Plugin! */
		/*******************/

		// run for each call
		return this.each(function() {
			var $existingContent = $(this).clone();
			var $newContent = drawBox();

			if( opts.wrapContent ){
				if(opts.viewMultiple) {
					alert("Multiple containers are not allowed when wrapping content (set viewMultiple to false or wrapContent to false)");
				} else {
					$newContent.find('.round-body-content').html($existingContent);
					$(this).replaceWith($newContent);
				}
			} else {
				if(opts.viewMultiple) {
					$(this).append($newContent);
				} else {
					$(this).html($newContent);
				}
			}
		});


		/********************/
		/* Plugin Functions */
		/********************/

		/*
		 * Creates rounded corner box around a selected element
		 * @return object
		 */
		function drawBox () {

			var $topBar = $('<div class="round-top back-right-image"><div class="round-top-fill back-left-image"></div></div>');

			if(opts.headerTitle != ""  && opts.headerThickness == "thick") {
				var $text = $('<span class="text-top"></span>').html(opts.headerTitle)

				$topBar.find('.round-top-fill').html($text);
			}

			var $bodyContent = $('<div class="round-body"><div class="round-body-content"></div></div>');

			if(opts.type != "" && !opts.wrapContent){
				var content = "";

				content	+= '<div class="message-body">';
				for (i in messages) {
					if (typeof i != typeof function(){}) {
						if(i > 0) {
							for( j=0; j < messages[i].length; j++) {
								content	+= '<div class="message-detail">' + messages[i][j] + '</div>';
							}
						} else {
							content	+= '<div class="message-summary">' + messages[i][0] + '</div>';
							content	+= '<div class="message-detail">' + messages[i][1] + '</div>';
						}
					}

				};
				content	+= '</div>';

				$bodyContent.children().append(content);
			}

			var $bottomBar = $('<div class="round-bottom back-right-image"><div class="round-bottom-fill back-left-image"></div></div>');

			//for error/warning/success boxes that have no fill color specifications
			if(opts.fillColor != "")
				opts.fillColor = opts.fillColor + "-";

			var $container = $('<div class="round-container"></div>')
									.addClass(opts.borderColor+"-"+opts.fillColor+"container")
									.append($topBar)
									.append($bodyContent)
									.append($bottomBar);

			//add an ID to the container if specified
			if(opts.containerId != ""){
				$container.attr('id',opts.containerId);
			}

			//add header thickness class to container if specified
			if(opts.headerThickness != ""){
				$container.addClass(opts.headerThickness+"-top");
			}

			//add more classes if specified
			if(opts.containerClass != ""){	//FIX THIS...ONLY ADDS 1 CLASS
				$container.addClass(opts.containerClass);
			}

			$container.css({width : opts.width});

			return $container;
		}

	}

})(jQuery);
jQuery(function() {
	//set focus to the whois search input field when the user first arrives at the page
	jQuery('#whois-search-field').focus();

	//set carousel
	jQuery('#wcarousel').dtCarousel({goToPosition:1});

});


function switchWhoisTab(tabname) {
	jQuery('.whois-tabbed').each(function() {
		jQuery(this).hide();
		jQuery(this).removeClass('whois-tab-active');
	});
	jQuery('.whoisTabss ul.tab-strip').children().removeClass();
	jQuery('.whoisTabsDedicated ul.tab-strip').children().removeClass();
	jQuery('.whoisTabs ul.tab-strip').children().removeClass();
	jQuery('#'+tabname+'-view').addClass('tab-strip-active');
	jQuery('#whois-tab-'+tabname).addClass('whois-tab-active').show();
	switch(tabname) {
		case 'record':
			jQuery('#whois-tab-title').html('Whois Record');
			jQuery('#my-whois-help-container').hide();
			break;
		case 'profile':
			jQuery('#whois-tab-title').html('Site Profile and Search Rank');
			jQuery('#my-whois-help-container').hide();
			break;
		case 'registration':
			jQuery('#whois-tab-title').html('Registration');
			jQuery('#my-whois-help-container').hide();
			break;
		case 'server':
			jQuery('#whois-tab-title').html('Server Data');
			jQuery('#my-whois-help-container').hide();
			break;
		case 'my':
			jQuery('#whois-tab-title').html('My Whois View');
			jQuery('#my-whois-help-container').show();
			break;
	}
	jQuery.get("/track/whois/tab/"+tabname+"-view");
	return false;
}

function buySingleCCTLDs(ccTld) {
	jQuery('#cTldFormDomains').val(ccTld);
	jQuery('#cTldForm').submit();
}

function buyCCTLDs() {
	checkedDomains = '';
	allDomains = '';
	jQuery('#ccTLDs-tab-body input[type=checkbox]').each(function() {
		allDomains += this.value + ':1;';
		if(this.checked)
			checkedDomains += this.value + ':1;';
	});

	if(checkedDomains == '') {
		checkedDomains = allDomains;
	}

	jQuery('#cTldFormDomains').val(checkedDomains);
	jQuery('#cTldForm').submit();
}

/*******************************/
/* ADD CONTENT TO MY WHOIS TAB */
/*******************************/
function loadMyWhoisJS(item,title,rowIndex) {

	var $clonedRow = jQuery('#'+item).clone();
	$clonedRow.attr({"id":item + "-copy"}).addClass('container-row');
	$clonedRow.children(".add-to-my-whois").remove();
	$clonedRow.children(".form-field").removeClass("wide-80").addClass("wide-70").css('float', 'left');
	var $removeImg = jQuery('<div class="remove-from-my-whois" title="Remove from My Whois" style="display:none;"><span onclick="removeMyWhoisContent(\''+ item +'\',\''+title+'\');"></span></div>');
	var $moveRowImgs = jQuery(
		'<div class="move-my-whois-row" title="Move this row one position up" style="display: none;">' +
		'<span class="button-up" onclick="moveMyWhoisContent(\''+ item +'\',\''+title+'\', \'up\');" ' +
		'onmouseover="jQuery(this).css(\'background\', \'transparent url(/images/stackup_depressed.gif) no-repeat scroll 0 0\'); return false;" ' +
		'onmouseout="jQuery(this).css(\'background\', \'transparent url(/images/stackup.gif) no-repeat scroll 0 0\'); return false;"> ' +
		'</span></div><div class="move-my-whois-row" title="Move this row one position down" style="display: none;">' +
		'<span class="button-down" onclick="moveMyWhoisContent(\''+ item +'\',\''+title+'\', \'down\');" ' +
		'onmouseover="jQuery(this).css(\'background\', \'transparent url(/images/stackdown_depressed.gif) no-repeat scroll 0 0\'); return false;" ' +
		'onmouseout="jQuery(this).css(\'background\', \'transparent url(/images/stackdown.gif) no-repeat scroll 0 0\'); return false;"></span></div>');
	$clonedRow.css({"background":"#FAFAFA"});
	$clonedRow.bind("mouseover",function() {
		jQuery('.container-row').trigger('mouseout'); // Rarely, a row will not get its mouseout event triggered if the mouse moves off an up/down button and out of a row very quickly
		$clonedRow.css({"background":"#E9EFF1"});
		$removeImg.show();
		$moveRowImgs.show();
		jQuery('.move-my-whois-row:first').find('.button-up').css('background', 'transparent url(/images/stackup_depressed.gif) no-repeat scroll 0 0');
		jQuery('.move-my-whois-row:last').find('.button-down').css('background', 'transparent url(/images/stackdown_depressed.gif) no-repeat scroll 0 0');
		return false;
	});
	$clonedRow.bind("mouseout", function(event) {
		if(!jQuery(event.target).html()) {
			// Without this, IE flickers when the mouse leaves the up/down arrow buttons.
			return false;
		}
		$clonedRow.css({"background":""});
		$removeImg.hide();
		$moveRowImgs.hide();
		return false;
	});

	//must update ID's for flyout purposes since this is a copy of the original
	if(item == 'rTab-gtld'){
		$clonedRow.find('.flyout-local').each(function(i,data){
			var $currentID = jQuery(data).attr('id');
			jQuery(data).attr({"id":$currentID + "-copy"});

			//for TLD flyouts of thumbnails
			jQuery(data).cluetip({
				local: true,
				width: "275px",
				arrows: false,
				showTitle:false
			});
		});
	}

	$clonedRow.prepend($removeImg);
	$clonedRow.append($moveRowImgs);

	$clonedRow.appendTo('#myWhoisContainer');
}

function loadPlaceholderRow(item,title) {

	var $removeImg = jQuery('<div class="remove-from-my-whois" title="Remove from My Whois" style="display:none;"><span onclick="removeMyWhoisContent(\''+ item +'\',\''+title+'\');"></span></div>');
	var $moveRowImg = jQuery(
		'<div class="move-my-whois-row" title="Move this row one position up" style="display: none;">' +
		'<span class="button-up" onclick="moveMyWhoisContent(\''+ item +'\',\''+title+'\', \'up\');" ' +
		'onmouseover="jQuery(this).css(\'background\', \'transparent url(/images/stackup_depressed.gif) no-repeat scroll 0 0\'); return false;" ' +
		'onmouseout="jQuery(this).css(\'background\', \'transparent url(/images/stackup.gif) no-repeat scroll 0 0\'); return false;">' +
		'</span></div><div class="move-my-whois-row" title="Move this row one position down" style="display: none;">' +
		'<span class="button-down" onclick="moveMyWhoisContent(\''+ item +'\',\''+title+'\', \'down\');" ' +
		'onmouseover="jQuery(this).css(\'background\', \'transparent url(/images/stackdown_depressed.gif) no-repeat scroll 0 0\'); return false;" ' +
		'onmouseout="jQuery(this).css(\'background\', \'transparent url(/images/stackdown.gif) no-repeat scroll 0 0\'); return false;"></span></div>');
	var $emptyRow = jQuery('<div id="'+item+'-copy" class="float-row display-input">' +
						'<div class="form-label field-label wide-20"><span>'+title+':</span></div>' +
						'<div class="form-field wide-80 normal">No data found for this domain.</div>' +
					'</div>');

	$emptyRow.css({"background":"#FAFAFA"});
	$emptyRow.bind("mouseover",function() {
		$emptyRow.css({"background":"#E9EFF1"});
		$removeImg.show();
		$moveRowImg.show();
		return false;
	});
	$emptyRow.bind("mouseout",function() {
		$emptyRow.css({"background":""});
		$removeImg.hide();
		$moveRowImg.hide();
		return false;
	});

	$emptyRow.prepend($removeImg);
	$emptyRow.append($moveRowImg);
	$emptyRow.appendTo('#myWhoisContainer');
}

//Function ONLY called on load of the my whois tab
function loadMyWhoisContent() {
	var $loader = jQuery("<div class='float-row ajax-loader'>Loading, please wait...</div>");
	$loader.appendTo('#myWhoisContainer');
	jQuery.getJSON('/?ajax=mWhois&call=getMyWhoisSections',function(data) {
		var rowIndex = 0;
		jQuery.each(data, function(rowID,rowTitle){
			if(jQuery('#'+rowID).length > 0) {
				loadMyWhoisJS(rowID,rowTitle, rowIndex);
				rowIndex++;
			} else {
				loadPlaceholderRow(rowID,rowTitle);
			}

			jQuery('#'+rowID+' .add-to-my-whois')
				.addClass('included-on-my-whois')
				.attr({"title":"Already included in My Whois"})
				.children("span").attr({"onclick":""})
				.unbind("click");
		});
		$loader.remove();
	});
	return false;
}

//Function to add rows of content to the My Whois tab
function addMyWhoisContent(IDToShow,rowTitle) {
	jQuery('#'+IDToShow+' .add-to-my-whois').addClass('adding-to-my-whois');
	jQuery.get('/?ajax=mWhois&call=addMyWhoisSection&args[0]='+IDToShow+'&args[1]='+rowTitle,function(data) {
		if(data) {
			jQuery('#'+IDToShow+' .add-to-my-whois')
				.removeClass('adding-to-my-whois')
				.addClass('included-on-my-whois')
				.attr({"title":"Already included in My Whois"})
				.children("span").attr({"onclick":""})
				.unbind("click");
			loadMyWhoisJS(IDToShow,rowTitle);
		}
	});
	return false;
}

//Function to remove rows of content from the My Whois tab
function removeMyWhoisContent(IDToHide,title) {
	jQuery.get('/?ajax=mWhois&call=removeMyWhoisSection&args[0]='+IDToHide,function(data) {
		if(data) {
			jQuery('#'+IDToHide+'-copy').remove();
			jQuery('#'+IDToHide+' .add-to-my-whois')
				.removeClass('included-on-my-whois')
				.attr({"title":"Add to My Whois"})
				.children("span").click(function() {
					addMyWhoisContent(IDToHide,title);
				});
		}
	});
}

//Function to move rows of content within the My Whois tab
function moveMyWhoisContent(ID,rowTitle,direction) {
	jQuery.getJSON('/?ajax=mWhois&call=moveMyWhoisSection&args[0]='+rowTitle+'&args[1]='+direction,function(data) {
		if(data && !data['no_move']) {
			jQuery('.ajax-loader').dtSpinnerToggle(true);
			jQuery('#' + data['element2ID'] + '-copy').fadeOut(function() {
				jQuery('#' + data['element2ID'] + '-copy').swap('#' + data['element1ID'] + '-copy');
				//jQuery('#' + data['element1ID'] + '-copy').trigger('mouseover');
				//jQuery('#' + data['element2ID'] + '-copy').trigger('mouseover');
				jQuery('#' + data['element2ID'] + '-copy').fadeIn();
				jQuery('.button-up').trigger('mouseout');
				jQuery('.button-down').trigger('mouseout');
			});
			setTimeout(function(){ jQuery('.ajax-loader').dtSpinnerToggle(false); }, 500);
		}
	});
	return false;
}

//Show/hide help link/content due to user preferences
function helpLink() {
	if(jQuery('#mwTab-intro:visible').length > 0) {
		jQuery('#mwTab-intro').hide();
		jQuery('.help_link_show').show();
		jQuery('.help_link_hide').hide();

		//set pref to hidden
		jQuery.get('/?ajax=mWhois&call=setMyWhoisInfoVisible&args[0]=0');
	} else {
		jQuery('#mwTab-intro').show();
		jQuery('.help_link_hide').show();
		jQuery('.help_link_show').hide();

		//set pref to visible
		jQuery.get('/?ajax=mWhois&call=setMyWhoisInfoVisible&args[0]=1');
	}

	return false;
}

jQuery.fn.swap = function(b) {
    b = jQuery(b)[0];
    var a = this[0];

    var t = a.parentNode.insertBefore(document.createTextNode(''), a);
    b.parentNode.insertBefore(a, b);
    t.parentNode.insertBefore(b, t);
    t.parentNode.removeChild(t);

    return this;
};

function addDomainMonitor(domain, server_current_domain) {
	jQuery.ajax({
        async: true,
        url: '/?ajax=mDomainMonitor&call=add_domain_default_portfolio',
        dataType: 'json',
        data: { domains: domain },
        success: function(result) {
            jQuery('#add-domain-monitor').replaceWith('Domain has been added to your <a class="pad-none" href="http://www.'+server_current_domain+'/monitor/">Domain Monitor</a>.');
        }
    });
}


<!--
var google_adnum = 0;
var whois_ad_format = 'wide';
var text_ad_count = 3;
var global_wide_ad_count = 0;
var global_google_adnum = 0;

function wrap_visible_url(string, max) {
	if(string.length <= max)
	    return string;
	dashPos = string.indexOf('-');
	if(dashPos > -1 && dashPos <= max)
	    return string;
	slashPos = string.indexOf('/');
	if(slashPos > -1 && slashPos <=max )
	    return string.substr(0,slashPos+1) + '<br>' + string.substr(slashPos+1,60);
	return string.substr(0, max-1) + '<br>' + string.substr(max,70);
}

function google_ad_request_done(google_ads) {
	var feedback = '';
	var dtAd = '';
	var i;
	var img_ads = [];
	var txt_ads = [];
	var img_i = 0;
	var txt_i = 0;
	var slotCountTop = 0;
	var googleAdsTop = '';

	if (google_ads.length == 0)
	{
		//alert('got me no ads');
		return;

	}
	if (google_ads.length == 1) {
		dtAd = '<div class="dt-ads-container dt-ads-wide">'
					+'<span class="dt-ads-wide1">'+ad_slot_nine+'</span>'
				+'</div>';
		feedback = '<a href=\"' + google_info.feedback_url + '\" class="google-ads-feedback">Ads by Google</a>';
	} else {
		feedback = '<a href=\"' + google_info.feedback_url + '\" class="google-ads-feedback">Ads by Google</a>';
	}

	for(i=0; i<google_ads.length; ++i) {
	    if(google_ads[i].type =="image")
	        img_ads[img_i++] = google_ads[i];
	    else if(google_ads[i].type=="text")
	        txt_ads[txt_i++] = google_ads[i];
	}

	slotCountTop=txt_ads.length;
	if(slotCountTop > 3)
	  slotCountTop=3;

	for(i=0;i<slotCountTop;i++) {
	        googleAdsTop +=
	        '<div class="google-ads-wide google-ads-wide' + slotCountTop + '">' +
	        '<a class="google-ads-title google-ads-title-wide' + slotCountTop + '" href="' +
	        txt_ads[i].url + '" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to ' +
	        txt_ads[i].visible_url + '\';return true;">' +
	        //txt_ads[i].line1 + '</a><span class="google-ads-wide' + slotCountTop + ' google-ads-body">' +
	        txt_ads[i].line1 + '</a><span class="google-ads-wide' + slotCountTop + '">' +
	        txt_ads[i].line2;
	        if(slotCountTop <= 3)
	            googleAdsTop += '&nbsp;';
	        googleAdsTop +=
	        //txt_ads[i].line3 + '</span><a class="google-ads-wide' + slotCountTop + ' google-ads-displayurl-wide" href="' +
	        txt_ads[i].line3 + '</span><a class="google-ads-wide' + slotCountTop + ' google-ads-displayurl-wide" href="' +
	        txt_ads[i].url + '" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to ' +
	        txt_ads[i].visible_url + '\';return true">' +
	        wrap_visible_url(txt_ads[i].visible_url,26) + '</a></div>';
	}

	//only pertinent to the whois ads?

	if(googleAdsTop != '')
	{
		var addition = '<div class="googleAdsTop"><div class="google-ads-feedback-wide">'+ feedback + '</div>' + googleAdsTop + dtAd +'<div class="clear">&nbsp;</div></div><div class="clear">&nbsp;</div></div>';

		//i would much rather use jQuery('#whois-ads-wide').html(addition), but thanks to IE6, i can't.  for some inane reason
		//it will not work.
		document.getElementById('whois-ads-wide').innerHTML = addition;

		if(dtAd != ''){
			jQuery('.google-ads-wide1').removeClass('google-ads-wide1').addClass('google-ads-wide2');
		}
	}

	if(google_ads[0].bidtype=="CPC") {/* insert this snippet for each ad call */
	  global_google_adnum = global_google_adnum + google_ads.length;
	}

	//reset the size of the main container when less than 3 ads appear
	if (google_ads.length < 3){
		//jQuery('#google-ad-table-right .left-ad-set').attr('width','550');
		jQuery('.googleAdsTop').css({'width': 550});
	}

	return;
}
//-->


