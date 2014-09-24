
/* Modernizr 2.7.1 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-cssanimations-cssclasses-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function z(a){j.cssText=a}function A(a,b){return z(m.join(a+";")+(b||""))}function B(a,b){return typeof a===b}function C(a,b){return!!~(""+a).indexOf(b)}function D(a,b){for(var d in a){var e=a[d];if(!C(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function E(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:B(f,"function")?f.bind(d||b):f}return!1}function F(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return B(b,"string")||B(b,"undefined")?D(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),E(e,b,c))}var d="2.7.1",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x={}.hasOwnProperty,y;!B(x,"undefined")&&!B(x.call,"undefined")?y=function(a,b){return x.call(a,b)}:y=function(a,b){return b in a&&B(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.cssanimations=function(){return F("animationName")};for(var G in q)y(q,G)&&(v=G.toLowerCase(),e[v]=q[G](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)y(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},z(""),i=k=null,e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.testProp=function(a){return D([a])},e.testAllProps=F,e.testStyles=w,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};




 /*------------- TOP SLIDER -------------*/

jQuery(document).ready(function($) {

if($('body').hasClass('not-responsive')) {
  $('#responsiveness-css').remove();
}

if ($('.top-slider .rev_slider').size() > 0) {
  var rev_id = $('.top-slider .rev_slider').attr('id');
      rev_id = rev_id.replace('rev_slider_','');
      rev_id = rev_id.replace('_2','');

  var revapi_id = eval('revapi'+rev_id);

  revapi_id.bind("revolution.slide.onloaded",function (e) {

      $('.top-slider').animate({height:$('.rev_slider').height()},300, function(){$('.top-slider').height('auto')});

  });
}

if ($('.banner-builder').size() > 0) {
  setTimeout(function(){$('.top-slider').animate({height:$('.banner-builder').height()},700, function(){$('.top-slider').height('auto')})}, 1000);
}

  /*----------------<RESIZE COMPLITE>----------------*/
    var zi = $('.media_for_js').css('z-index');
        zi_p = zi;




    jQuery.fn.resizeComplete = function(func, ms){


        var timer = null;
        this.resize(function(){
            if (timer)
                {
                clearTimeout(timer);
                }
              timer = setTimeout(func,ms);
        });
    }
  /*----------------</RESIZE COMPLITE>----------------*/


  /*---------- Sidebar Posiyion ----------*/

  function sidebarPosition(){
    if($('.media_for_js').css('z-index') < 767) {
      $('aside.left-sidebar').insertAfter('.post-area');
    } else {
      $('aside.left-sidebar').insertBefore('.post-area');
    }
  }
  sidebarPosition();
  /*---------- /Sidebar Posiyion ----------*/



  /*----------------<COLUMN>----------------*/
  jQuery(function(){
    var $column_post_media = jQuery('.column_post-media'),
    $column = jQuery('.template-blog-3 .column');
    $column_post_media.css('height', $column_post_media.width()*0.5);
    $column_post_media.find('iframe').css('height', $column_post_media.width()*0.5);
    $column.isotope({
      itemSelector: '.column_post',
      resizable: true,
      animationEngine: 'best-available',
      animationOptions: {
        duration: 800,
        easing: 'swing',
        queue: false
      }
    });
  });
  jQuery(window).resizeComplete(function(){
    var $column_post_media = jQuery('.column_post-media'),
    $column = jQuery('.template-blog-3 .column');
    $column_post_media.css('height', $column_post_media.width()*0.5);
    $column_post_media.find('iframe').css('height', $column_post_media.width()*0.5);
    $column.isotope({
      itemSelector: '.column_post',
      resizable: false,
      animationEngine: 'best-available',
      animationOptions: {
        duration: 800,
        easing: 'swing',
        queue: false
      }
    });

    sidebarPosition();


    jQuery('.gallery-container .gallery .item-con-t1').each(function () {
      jQuery(this).css({'margin-bottom': jQuery(this).css('margin-right')});
    });
      isotope_gal();
    }, 500);

  /*----------------</COLUMN>----------------*/

  /*--------------- CONTACT FORM ---------------*/

  var inputs = jQuery('.input-field input, .input-field textarea');
  inputs.focus(function(){
    jQuery(this).parents('.input-field').addClass('focus');
  });
  inputs.blur(function(){
    jQuery(this).parents('.input-field').removeClass('focus');
  });

  jQuery('#searchform input[type=text]').focus(function(){
    jQuery(this).parents('form').addClass('focus');
  });
  jQuery('#searchform input[type=text]').blur(function(){
    jQuery(this).parents('form').removeClass('focus');
  });

  jQuery('.wysija-input').focus(function(){
    jQuery(this).addClass('focus');
  });
  jQuery('.wysija-input').blur(function(){
    jQuery(this).removeClass('focus');
  });
  /*--------------- CONTACT FORM ---------------*/

  /*----------------<POST>----------------*/
  // jQuery(window).load(function(){
  //  var $item_con_t1_l = jQuery('.post.left .item-con-t1'),
  //  $item_con_t1_r = jQuery('.post.right .item-con-t1'),
  //  $item_con_t1_c = jQuery('.post.center .item-con-t1');
  //  $item_con_t1_l.css('height', $item_con_t1_l.width() * 0.77);
  //  $item_con_t1_r.css('height', $item_con_t1_r.width() * 0.77);
  //  $item_con_t1_c.css('height', $item_con_t1_c.width()/2);

 //  skills_animation();
  // });
  // jQuery(window).resize(function(){
  //  var $item_con_t1_l = jQuery('.post.left .item-con-t1'),
  //  $item_con_t1_r = jQuery('.post.right .item-con-t1'),
  //  $item_con_t1_c = jQuery('.post.center .item-con-t1');
  //  $item_con_t1_l.css('height', $item_con_t1_l.width() * 0.77);
  //  $item_con_t1_r.css('height', $item_con_t1_r.width() * 0.77);
  //  $item_con_t1_c.css('height', $item_con_t1_c.width()/2);
  // });
  /*----------------</POST>----------------*/

  /*----------------<ACCORDION>----------------*/
  jQuery('.widget_accordion .button, .widget_accordion header').click(function () {
    if (!jQuery(this).parents('.item').hasClass('active')) {
      if($(this).parents('.widget_accordion').is('.accordion_style3')) {
        jQuery(this).parents('.accordion_style3').find('.item-container').slideUp(500);
      jQuery(this).parents('.accordion_style3').find('.item').removeClass('active');
        console.log('close')
      }
      jQuery(this).parent().find('.item-container').slideDown(500);
      jQuery(this).parents('.item').addClass('active');
      jQuery(this).find('span:first').fadeOut(300);
    } else {
      jQuery(this).parent().find('.item-container').slideUp(500);
      jQuery(this).parents('.item').removeClass('active');
      jQuery(this).find('span:first').fadeIn(300);
    }
  });
  /*----------------</ACCORDION>----------------*/

    /*----------------<HEADLINE>----------------*/


    var summar_width = 0;
    var menu_height = jQuery('.page-header .menu>li>a, .page-header .menu>ul>li>a').size() * 44;
    jQuery('.page-header .menu>li>a, .page-header .menu>ul>li>a').each(function(){
      summar_width += jQuery(this).width()+65;
    })

  function headline(){
     var outer_width = jQuery('.page-header .menu').parents('.container').width();
    if (outer_width+10 >  summar_width)
    {
      jQuery('body').removeClass('mobile');

      jQuery('.headline').css({'margin-bottom': '0'});

       jQuery('header  .menu').removeAttr('style');
     jQuery('.menu .sub-menu, .menu .children').removeAttr('style');
    }
    else
    {
      jQuery('.menu .sub-menu, .menu .children').animate({height:"hide"},0);
      jQuery('body').addClass('mobile');
      jQuery('.absolute').css({'position': 'relative', 'top': '0'});

      jQuery('.headline').css({'margin-bottom': '-54px'});
      // jQuery('.header-image').css({'height': '54px'});
       jQuery('header  .menu').css('height','0');
    }

  }

  // headline();
  // setTimeout(headline, 500);
  // jQuery(window).resize(function(){
  //  headline();
  // });

  jQuery('#menu-btn').click(function() {
   if (jQuery('.menu').height() > 0) {
     jQuery('.menu').animate({height:0},300).removeClass('opened');
   } else {
     jQuery('.menu').animate({height:menu_height},300, function() { jQuery(this).removeAttr('style').addClass('opened')});
    }
    return false;
  });

  // jQuery('.menu>li').click(function() {
  //    jQuery(this).children('.sub-menu').animate({height:'toggle'},300);
  //  // if (jQuery(this).children('.sub-menu').height() > 0) {
  //  //   jQuery(this).children('.sub-menu').animate({height:0},300);
  //  // } else {
  //  //   jQuery(this).children('.sub-menu').animate({height:menu_height},300, function() { jQuery(this).removeAttr('style')});
  //  //  }
  //   return false;
  // });
  /*----------------</HEADLINE>----------------*/

  /*----------------<ITEM CONTAINER TYPE1>----------------*/
    var cont_i = 500;
    var image_loaded = false;
    function item_cont_t1() {
        // $('.container-t1-margin').each(function(){

        //     jQuery(this).css('height', Math.ceil(jQuery(this).parent().parent().height())-8+'px');
        //     var ratio_cont = jQuery(this).width()/jQuery(this).height();
        //     var $img = jQuery(this).find('img');
        //     var $image_links = jQuery(this).find('.image-links');
        //     var $gallery_image_links = jQuery(this).find('.gallery-image-links');
        //     var $widget_recent_posts_2_fac = jQuery(this).find('.widget_recent_posts_2-fac');
        //     var ratio_img = $img.width()/$img.height();
        //     $image_links.css('margin-left', (jQuery(this).width()-$image_links.width())/2+'px');
        //     $gallery_image_links.css('margin-left', (jQuery(this).width()-$gallery_image_links.width())/2+'px');
        //     $widget_recent_posts_2_fac.css('margin-left', (jQuery(this).width()-$widget_recent_posts_2_fac.width())/2+'px');
        //     if (ratio_cont > ratio_img)
        //     {
        //         $img.css({'width': '100%', 'height': 'auto', 'top': -50*(1/ratio_img-1/ratio_cont)*ratio_cont+'%'});
        //     }
        //     else if (ratio_cont < ratio_img)
        //     {
        //         $img.css({'width': 'auto', 'height': '100%', 'left': -50*(ratio_img-ratio_cont)/ratio_cont+'%'});
        //     }
        //     $img.css('display','block');

        // });

        jQuery('.gallery-container .gallery > article, .item-con-t1, .z-tabs').each(function(){
          var cont =jQuery(this);
      var sp = (cont.is('.trans03linear') || cont.is('.isotope-item')) ? 0 : 500;
          setTimeout(function(){
              cont.animate({opacity:1},sp);
            },cont_i);
            cont_i += 100;
        });
        image_loaded = true;
    }

    jQuery(window).load(function(){
        // setTimeout(item_cont_t1, 0);
        setTimeout(item_cont_t1, 100);
    //  if ($('.sc-highlight').size() > 0) {
    //  $(window).scrollTop($(window).scrollTop()+1);
    //  setTimeout(function(){
    //    $(window).scrollTop($(window).scrollTop()-1);
    //  }, 3000);
    // }
    });
    var ss=0;
    jQuery(window).resize(function(){
      //  item_cont_t1();
    });
    jQuery(window).load(function(){
        //setTimeout(function(){ $(window).resize()}, 500);
    });
  /*----------------</ITEM CONTAINER TYPE1>----------------*/

  /*----------------<GALLERY>----------------*/
  function isotope_gal() {
    // jQuery('.gallery-container .gallery .item-con-t1').each(function () {
    //  jQuery(this).css({'height': Math.ceil(jQuery(this).height()), 'margin-bottom': jQuery(this).css('margin-right')});
    // });
    var $gallery = jQuery('.gallery-container .gallery'), $optionSets = jQuery('.gallery-filters li'), $optionLinks = $optionSets.find('a');
    $gallery.imagesLoaded(function(){
    $gallery.css('display', 'block');
    $gallery.isotope({
      itemSelector : '.item-con-t1',
      resizable: false,
      animationEngine: 'best-available',
      animationOptions: {
        duration: 800,
        easing: 'swing',
        queue: false
      }
    });
    $optionLinks.click(function(){
      var $this = jQuery(this), selector = $this.attr('data-filter');
      if ( $this.hasClass('selected') ) {
        return false;
          }
      $optionSets.find('.selected').removeClass('selected');
          $this.addClass('selected');
      $gallery.isotope({ filter: selector });
      return false;
    });
    });
  }

  setTimeout(isotope_gal,1000);
  /*----------------</GALLERY>----------------*/


  /*----------------<Back To Top>----------------*/
    $('#back_to_top').fadeOut(300);
    $(window).load().scroll(function(){
        if($(window).scrollTop() > 100){
            $('#back_to_top').fadeIn(300);
        }  else {
            $('#back_to_top').fadeOut(300);
        }
    });
    $('#back_to_top').click(function(e){
    e.preventDefault();
        $('html,body').animate({scrollTop:0},1000);
    });

  /*----------------</Back To Top>----------------*/

  /*----------------<PRETTY PHOTO>----------------*/

  /*----------------</PRETTY PHOTO>----------------*/

  /*------------------- Fixed Header ---------------*/
 if ($('.headerstyle2 .logo img, .headerstyle4 .logo img').size() > 0) {
  $('.headerstyle2 .logo img, .headerstyle4 .logo img').attr('id','logo');
    setTimeout(function(){$('.headerstyle2 .logo img, .headerstyle4 .logo img').height(document.getElementById('logo').naturalHeight)  },500);
    setTimeout(function(){$('.headerstyle2 .logo img, .headerstyle4 .logo img').css('opacity','1')} ,800);
  }
  if($('body').hasClass('sticky-menu-on') && $('.media_for_js').css('z-index')>767) {
  //console.log(document.getElementById('logo').naturalHeight)
  if($('.media_for_js').css('z-index')>767) {
    jQuery(window).scroll(function() {
      var st;

  if ($('body').hasClass('headerstyle7') && $('.top-slider').length > 0) {
      st = $('.top-slider').height();
  } else if ($('body').hasClass('headerstyle2')) {
      if ($('.preheader').size()>0) {
        st=$('.preheader').height();
      } else {
        st=3;
      }
    } else if ($('body').hasClass('headerstyle4')) {
      st=3;
    } else if ($('body').hasClass('headerstyle5')) {
      st=35;
  } else {
      st=100;
    }

      if(jQuery(window).scrollTop() > st) {
          jQuery('body').addClass('fixed-header');


          jQuery('.page-header .menu-bg').addClass('fixed');

      } else {
          jQuery('body').removeClass('fixed-header');


          jQuery('.page-header .menu-bg').removeClass('fixed');

      }
    });
  }
}
  /*------------------- Fixed Header ---------------*/

  /*---------- Banner Loading -----------*/
  if($('.banner-wrapper').size() > 0) {
    var banner = $('.banner-wrapper');
    setTimeout(function(){
      banner.find('#canvasLoader').hide();
      banner.find('.banner-overlay').animate({opacity:0},500);
    },4000);


    var cl = new CanvasLoader('canvasloader-container');
    cl.setColor('#2aa3cf'); // default is '#000000'
    cl.setShape('spiral'); // default is 'oval'
    cl.setDiameter(48); // default is 40
    cl.setDensity(99); // default is 40
    cl.setRange(1.2); // default is 1.3
    cl.setSpeed(4);
    cl.setFPS(43); // default is 24
    cl.show(); // Hidden by default
  }
  /*---------- Banner Loading -----------*/


/*--------------------- Animated Pictures ---------------*/

var anim_block, anim_elem = $('.animated'), gn = 1;

if (anim_elem.size() > 0) {
anim_elem.each(function(){
  //jQuery(this).parents('.wrapper').addClass('animated-block');
  var  el_scr = $(this).offset();
  if($('.group1').size()>0) {
  var prev_el = $('.group'+(gn-1)).offset();
    prev_el_top = prev_el.top;
  } else  {
    prev_el_top = 0;
  }
  if (el_scr.top == prev_el_top) {
  $(this).addClass('group'+(gn-1));
  } else {
  $(this).addClass('group'+gn);
    gn++;
  }
  return gn;
});

  for (var g = 0; g < gn; g++) {

  var i=0;
  $('.group' + g).each(function(){
    $(this).css({
        '-webkit-transition-delay': i+'s',
        '-moz-transition-delay': i+'s',
        '-o-transition-delay': i+'s',
        '-ms-transition-delay': i+'s',
        'transition-delay': i+'s'
    });
    i=i+0.15;
  });
}
}

  function anim_images() {

  anim_elem.each(function(){

  var block_offset = $(this).offset();
    if ( $(window).scrollTop() + window.innerHeight > block_offset.top+$(this).height()/2) {
      $(this).addClass('animation_started');
      var el = $(this);
      setTimeout(function(){
        el.removeAttr('style');
      }, 3000);
    }
  });
}


    $(window).scroll(function() {
        anim_images();
    });

  $(window).load(function() {
    setTimeout(anim_images,300);
  setTimeout(function(){  $('.flexslider').animate({opacity:1},500);},0)
  });


/*--------------------- Animated Pictures ---------------*/



/*--------------------- REVOLUTION SLIDER---------------*/

$('.rev-next').click(function(){
    $('.tp-rightarrow').click();
    return false;
});
$('.rev-prev').click(function(){
    $('.tp-leftarrow').click();
    return false;
});

/*--------------------- REVOLUTION SLIDER---------------*/

/*---------------------- SKILLS ANIMATION ----------------------*/

$('.member-skills span').each(function(){
  var skill_width = $(this).attr('style').match(/\d+/)[0];
  $(this).width(0).css('opacity','1').animate({width:skill_width+'%'},2000);
});

/*---------------------- SKILLS ANIMATION ----------------------*/


/*----------------- MENU -----------------------------*/

$('.menu li').each(function(){
  if ($(this).children('.sub-menu, .children').size()>0) {
    $(this).append('<span class="icon-angle-down"></span>').children('a').addClass('has-sub-menu');
    $(this).children('.sub-menu, .children').animate({height:"hide"},0);
  }
});

$('.menu li .has-sub-menu').siblings('span').click(function(){
  if($('#menu-btn').css('display') == 'inline-block') {
    if ($(this).parent('li').hasClass('open')){
      $(this).siblings('.sub-menu, .children').animate({height:"hide"},300);
      $(this).parent('li').removeClass('open');
    } else {
      $(this).siblings('.sub-menu, .children').animate({height:"show"},300);
      $(this).parent('li').addClass('open');
    }
    return false;
  } else {

  }
});

$('.menu li').hover(function(){
  if($('#menu-btn').css('display') == 'none') {
    $(this).children('ul').fadeIn(200);
  }
}, function(){
  if($('#menu-btn').css('display') == 'none') {
    $(this).children('ul').fadeOut(200);
  }
})


/*------------------- MENU -------------------*/


/*------------------------------- IE TRANSITIONS ----------------------------*/

  $('.no-csstransitions .item-con-t1').hover(function(){
    $(this).find('.visible-on-hover').stop().animate({opacity:1},400);
  }, function(){
    $(this).find('.visible-on-hover').stop().animate({opacity:0},400);
  });

/*------------------------------- IE TRANSITIONS ----------------------------*/

$('.top-compact, .bottom-compact').each(function(){
  $(this).removeAttr('style');
  var c = $(this).find('.z-tab').size();
  $(this).find('.z-tab').width(100/c+'%');
});

/*
$('.widget_out_stuff2 .item-con-t1 header h2').each(function(){
    var me = $(this);
    me.html( me.text().replace(/(^\w+)/,'<b>$1</b>') );
  });

*/

/*--------------------- Control Panel -------------------*/

$('#control-panel select').change(function(){
   window.location.href=$(this).find('option:selected').val();
});


/*------------- SHOP ----------------*/

$('.product > .button').wrap('<div class="product-bottom"></div>');
$('.product-bottom').each(function(){
  $(this).append('<a class="product-details" href="' + $(this).parents('.product').children('a').attr('href') +'">Details</a>');
});
$('.woocommerce-ordering').append('<span>Sort by</span><span class="icon-angle-down"></span>');
$('#calc_shipping_country').after('<span class="icon-angle-down"></span>');

$('.widget_shopping_cart').prepend('<a id="cart_button"></a>');

var cart = $('.widget_shopping_cart_content');
 $('.widget_shopping_cart_content').stop().animate({opacity:0},500, function(){$(this).hide()});
$('.widget_shopping_cart').hover(function(){
  $('.widget_shopping_cart_content').stop().show().animate({opacity:1},500);

}, function(){
  $('.widget_shopping_cart_content').stop().animate({opacity:0},500, function(){$(this).hide()});
});
var addi;
function if_added(item) {
    if (item.hasClass('added')) {
        clearInterval(addi);
        $('body').append('<div id="added_item"><img src="'+ item.parents('.product').find('.attachment-shop_catalog').attr('src') +'" alt=""/><b>"'+ item.parents('.product').find('h3').html() +'"</b> was added to the cart. </div>');
        $('#added_item').fadeIn(500);
        setTimeout(function(){
          $('#added_item').animate({opacity:0,marginTop:20},500, function(){$(this).remove()});
        },2000)
      }

  }

$('.add_to_cart_button').each(function(){
  var add_btn = $(this);
  $(this).click(function(){
    addi = setInterval( function() {if_added(add_btn)},100);
  })

});

/*------------- Tweets in the footer ----------------*/

$('#recent-tweets').flexslider({
  animation: "fade",
  controlNav: false,
  directionNav: false
});

$('.prev-t3').click(function(){
  $('#recent-tweets').flexslider("prev");
});

$('.next-t3').click(function(){
  $('#recent-tweets').flexslider("next");
});


/*------------ Rev slider nav ------------*/
var bul;
setTimeout(function(){
  bul = $('.tp-bullets .bullet');
  if ($('.revslider-nav2 li').size()>0) {
  $('.tp-bullets').addClass('nobullets');
}
  return bul;
},3000);

$('.revslider-nav2 li').click(function(){
  var bul_num = $(this).index();
  bul.eq($(this).index()).click();
});

/*------ Gmap -----*/

$(window).load(function(){
  $('.wpgmappity_container').parent('div').width('100%');
});

/*----------- Mega Menu -----------*/

function mega_menu(){
  var mega_m = $('li.mega-menu > .sub-menu');
  mega_m.each(function(){
    var mm = $(this);

    mm.css({
      display:'block',
      visibility:'hidden'
    });

    var marg = ($('body').is('.headerstyle2_2')) ? 20 :0;
    var m_w = mm.children('li').size()*(mm.children('li').width()+marg)+5;
    var mw_l = -(m_w/2)+100;
    mm.css({
      display:'none',
      visibility:'visible',
      marginLeft: mw_l
    });

    mm.width(m_w);
  });
}

mega_menu();

/*----------- Mega Menu -----------*/




  /*------------- Flexslider --------------*/

function flexsliderInit() {
  $('.flexslider').each(function(){
    fs = $(this);
    if(!$(this).parents().is('.top-slider')) {
    $(this).find('.flex-control-nav').remove();
    var iCount = 0, scrWidth = $('.media_for_js').css('z-index');

          var move = 1;
    if (fs.hasClass('full-width')) {
      var ml = -($(window).width() - fs.parents('.featured-projects').width())/2;
      fs.css({
        'margin-left': ml,
        width:$(window).width()
      });
      move=5;
      iCount = 5;
      console.log(ml)
    }

      if (scrWidth == 479) { iCount = 1} else if (scrWidth == 767 || scrWidth == 639) {iCount = 2} else {
        if ($(this).hasClass('widget_our_clients-container')) {
          iCount = 5;
        } else if ($(this).hasClass('full-width')) {
          iCount = 5;
        } else if ($(this).hasClass('featured-project-slider')) {
          iCount = 4;
        } else {
         iCount = 3
         }
      }


    var selector = ($(this).hasClass('fs-inner')) ? '.slides-inner > li' : '.slides > li';
    var slideshow = ($(this).hasClass('fs-inner')) ? true : false;
    if ($(this).hasClass('fs-inner') || $(this).hasClass('images-slider') || $(this).hasClass('widget_testimonials-container') || $(this).parent('div').hasClass('flexslider-testimonials')  || $(this).parent('div').hasClass('portfolio-gallery') || $(this).parents('article').hasClass('format-gallery') || $(this).parent('section').hasClass('sc-testimonial-slider')) {
     iCount = 1;
  }
    var animation = ($(this).is('.gallery-slider')) ? 'fade' : 'slide';

    if($(this).is('.thumbnail-slider')) {
      iCount = 5;
      var sync = '#'+$(this).siblings('.gallery-slider').attr('id');
      console.log(sync)
    }
    if($(this).is('.gallery-slider')) {
      var asnav = '#'+$(this).siblings('.thumbnail-slider').attr('id');
      console.log(asnav)
    }
    var cnav = ($(this).hasClass('control-nav')) ? true : false,
        dnav = ($(this).hasClass('direction-nav')) ? true : false;
    $(this).removeData("flexslider").flexslider({
                      animation: animation,
                      selector: selector,
                      animationLoop: true,
                      itemWidth: 200,
                      itemMargin: 0,
                      smoothHeight: true,
                      slideshow: slideshow,
                      controlNav: false,
                      directionNav: true,
                      slideshowSpeed: 7000,
                      minItems: iCount,
                      maxItems: iCount,
                      startAt: 0,
                      sync: sync,
                      asNavFor: asnav,
                      move: move
      });
      $(this).find('.flex-viewport').each(function(){
         if ($(this).find(selector).size() == 0) { $(this).addClass('rem').hide()}
      })
      setTimeout(function(){$('.flex-viewport.rem').remove()},1000);
    }




    });

    // $('.thumbnail-slider li').hover(function() {
    //   var thumb_item =  $(this);
    //   $('.thumbnail-slider li').removeClass('flex-active-slide');
    //   thumb_item.addClass('flex-active-slide');
    //   thumb_item.click();
    //   var it = setTimeout(function(){ $('.thumbnail-slider li.flex-active-slide').click() },150);
    // }, function(){/*clearTimeout(it)*/})

    $('.thumbnail-slider li').on('mouseenter ', function(){
      console.log('click');
      $(this).click();
      setTimeout(function(){ $(this).click() },150)
    });


  }

 /*-------- Mouse touch effect -------------*/


  var fps_fw = $('.featured-project-slider.full-width');
  var fps_start, fps_end;
  fps_fw.mousedown(function(e){
    fps_start = e.pageX;
    return fps_start;
  });
  fps_fw.mouseup(function(e){
    if(fps_start - e.pageX < -50) {
          fps_fw.flexslider('prev')
    } else if (fps_start - e.pageX > 50) {
          fps_fw.flexslider('next')
    }
  });

  var lps_fw = $('.latest_posts_2-container');
  var lps_start, lps_end;
  lps_fw.mousedown(function(e){
   lps_start = e.pageX;
    return lps_start;
  });
  lps_fw.mouseup(function(e){
    if(lps_start - e.pageX < -50) {
          lps_fw.flexslider('prev')
    } else if (lps_start - e.pageX > 50) {
          lps_fw.flexslider('next')
    }
  });

  $(window).resizeComplete(function(){
    flexsliderInit();
    video_bg();
    if(image_loaded){
      setTimeout(item_cont_t1, 300);
    }
  },400);
  $(window).load(function(){
    flexsliderInit();
    video_bg();
  });



/*---------------- Highlight Video bg ----------------------*/

function video_bg() {
  if ($('.sc-highlight-full-width').find('video').size() > 0) {
   $('.sc-highlight-full-width').each(function() {
      var v = $(this).find('video');
      var pat = $(this).find('.video-pattern');
      if (v.size()>0) {
        $(this).css({
          'position':'relative',
          'overflow':'hidden'
        });
      }
      if (pat.size()>0) {
        pat.css({
          'opacity':1
        });
      }
      var ml = -($(window).width() - $(this).find('.sc-highlight').width())/2;
      v.css({
        'margin-left': ml,
        width:$(window).width(),
        opacity:1
      });
   });
  }
}


/*------------ Three Plates -----------------*/

 if ($('.three-plates').size()>0) {
  var pl = $('.three-plates').find('.plate');
  pl.width(100/pl.size()+'%');
  $('.three-plates').find('.plate:first-child').append('<div class="alpha tran03slinear"></div>')
  $('.three-plates').find('.plate:last-child').append('<div class="omega tran03slinear"></div>')
 }

/*---------- Mobile hover -------------*/

if ($('.media_for_js').css('z-index') < 767) {

  $('.item-con-t1').click(function(){
    $(this).find('.visible-on-hover').toggleClass('hover');
  });

//$(".featured-project").find('a').removeAttr('draggable');
  // $("a", $(".featured-project")).click(function(){

    // if ($(this).hasClass('hover')) {
    //   return true;
    // } else {
    //   $(this).addClass('hover');
    //   return false;
    // }
  // });
}


  // /*-------- Nice scroll ---------*/
    if($('html').hasClass('csstransitions') && $('body').hasClass('nice-scroll')){

    $("html").niceScroll({
        scrollspeed: 60,
        mousescrollstep: 35,
        cursorwidth: 16,
        cursorborder: 0,
        cursorcolor: '#7d7d7d',
        cursorborderradius: 12,
        autohidemode: false,
        horizrailenabled: false,
        zindex : 300,
        hwacceleration: true
    });

    if($('body').hasClass('b1170') || $('body').hasClass('b960')) {} else {
      $('html').css('padding-right','16px');
    }
  }
  // /*-------- Nice scroll ---------*/

  /*------------- Scroll to top --------------*/
  jQuery('.sc-divider-scroll').click(function(){
        jQuery('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });

  /*------------- Skillbar --------------*/
  jQuery('.sc-skillbar .sc-skillbar-bar').data('play','false');

  function skills_animation() {
  jQuery('.sc-skillbar .sc-skillbar-bar').each(function(){
  if (is_visible($('.sc-skillbar .sc-skillbar-bar')) && $(this).data('play') == 'false') {
      per = jQuery(this).attr('data-percentage');
      color = jQuery(this).attr('data-color');
    color_style = '';
    if (color != "")
    {
      color_style = 'background-color: ' + color;
    }
    var sb_per = (jQuery(this).parents('.sc-skillbar').hasClass('sc-skillbar-style-2')) ? '<span></span>' : '';
    jQuery(this).append('<div style="width: 0; ' + color_style + '">'+sb_per+'</div>');
    var bar = jQuery(this).children('div');
        setTimeout(function(){bar.css('opacity','1')},0)
       bar.animate({ "width" : per + "%"},
        {
          step:function(val){
//.match(/\d+/)[0];
              //var skill_width =  jQuery(this).attr('style')
                        //    .split(';')[1].split['%'][0];
              jQuery(this).find('span').html(parseInt(val)+1+'%');
               },
          duration:  per*30
        });
      $(this).data('play','true')
    }


  });
}

  /*------------- Skillbar vertical --------------*/
  jQuery('.sc-skillbar-vertical .sc-skillbar-bar').each(function(){
      per = jQuery(this).attr('data-percentage');
    color = jQuery(this).attr('data-color');
    color_style = '';
    if (color != "")
    {
      color_style = 'background-color: ' + color;
    }
    jQuery(this).append('<div style="width: 25px; height: 0px; ' + color_style + '"></div>');
      jQuery(this).children('div').delay(1000).animate({ "height" : per + "%"}, per*30);
  });


  /*----------- Rounded counter ---------------*/

  function is_visible(el) {
  var el_off = el.offset(),
     el_top = el_off.top;
     if ($(window).scrollTop() > el_top - window.innerHeight*0.9) {
      return true;
     }
  }

  $(window).load(function(){
  skills_animation();
  });

  $(window).scroll(function(){
  round_counter();
  skills_animation();
  });
  $('.sc-counter').data('play','false');
  $('.sc-counter-2').data('play','false');
  function round_counter() {

    $('.sc-counter, .sc-counter-2').each(function(){

      if (is_visible($(this)) && $(this).data('play') == 'false' ) {
        var qh = $(this).find('.sc-quantity'),
          q = qh.attr('data-quantity'),
          i = 0,
          timer = setInterval(function(){
            qh.html(i);
            i++;
            if(i>q) {
            clearInterval(timer);          }
          },10)
          $(this).data('play','true');
      }
    });
  }

  $('.sc-map-container').each( function() {
    $(this).find('.wpgmappity_container').height($(this).height());
  });



/*------- Search icon -------*/

$('#search-icon i').click(function(){
  if (!$(this).is('.active')) {
    $(this).addClass('active');
    $(this).siblings('form').find('input').fadeIn(300).focus();
  }
});

$('#search-icon form input').blur(function(){
  $(this).fadeOut(300);
    setTimeout(function(){$('#search-icon i').removeClass('active')},200);
});






$('.widget_left_navigation li .sub-menu').slideUp(0);
$('.widget_left_navigation .sub-menu').parent('ul').addClass('has-sub-menu');
$('.widget_left_navigation li.has-sub-menu a').click(function(){


})




  /*------- Form shortcode -------*/
  $(".sc-form-clear").click(function() {
    $(this).closest('form')[0].reset();
  });

  if ($('.sc-form').length > 0) {

    jQuery.extend(jQuery.validator.messages, {
      required: $('.sc-form').attr('data-required'),
      email: $('.sc-form').attr('data-email')
    });

    $('.sc-form').validate();
  }

  // POST LIKES
  $('.likes').click(function() {

    $post_id = $(this).attr('data-post-id');

    //end if clicked or cookie exists
    if ($(this).hasClass('clicked') || document.cookie.indexOf('saved_post_like_' + $post_id) != -1)
    {
      return;
    }

    $(this).html(parseInt($(this).html()) + 1);
    $(this).addClass('clicked');

    $current_post_like = this;

    $.ajax({
      type: 'GET',
      url: ajaxurl,
      data: {
        action: 'save_post_like',
        post_id: $post_id
      },
      success: function(data, textStatus, XMLHttpRequest){
        $($current_post_like).html(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
      }
    });
  });


  /*------------- Message -----------*/

  $('.sc-message').find('.close').click(function(){
    var msg = $(this);
    msg.parent('.sc-message').animate({opacity:0},300).animate({height:0},200, function(){msg.parent('.sc-message').remove()});
  });




  /* ---------- One Page Menu Scroll -------------- */
  $('#menu-one-page-menu a').click(function(e){

  e.preventDefault();

  var target = $(this).attr('href');
  var target_y = $(target).offset().top;

  $('body,html').animate({scrollTop:target_y-42+'px'}, 600);

  });




}); // DOCUMENT READY


// Grayscale w canvas method
function grayscale(src){
  var canvas = document.createElement('canvas');
  var ctx = canvas.getContext('2d');
  var imgObj = new Image();
  imgObj.src = src;
  canvas.width = imgObj.width;
  canvas.height = imgObj.height;
  ctx.drawImage(imgObj, 0, 0);
  var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
  for(var y = 0; y < imgPixels.height; y++){
    for(var x = 0; x < imgPixels.width; x++){
      var i = (y * 4) * imgPixels.width + x * 4;
      var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
      imgPixels.data[i] = avg;
      imgPixels.data[i + 1] = avg;
      imgPixels.data[i + 2] = avg;
    }
  }
  ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
  return canvas.toDataURL();
}
