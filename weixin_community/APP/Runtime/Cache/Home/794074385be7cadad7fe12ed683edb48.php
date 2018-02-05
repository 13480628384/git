<?php if (!defined('THINK_PATH')) exit(); if(is_array($friend_info)): $i = 0; $__LIST__ = $friend_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><div class="comm-total">
        <div class="com-top"><b class="c-name"><?php echo ($value["nickname"]); ?></b>  - <?php echo (str_substr($value["create_date"],10)); ?> </div>
        <div class="com-middle">
            <?php echo ($value["text"]); ?>
        </div>
        <!--图片显示 [[-->
        <?php if(!empty($value['pic'])){ ?>
        <ul class="pics">
            <li class="img_pics">
                <div class="demo-gallery" id="demo-test-gallery">
            <?php $pic = explode(',',$value['pic']); $pics = array(); foreach($pic as $p){ $pics[]['imgs'] = $p; } foreach($pics as $kpic=>$vpic){ if($kpic<=2){ ?>
                    <a href="<?php echo ($vpic["imgs"]); ?>" data-size="1600x1068" data-med="<?php echo ($vpic["imgs"]); ?>" data-med-size="1024x1024">
                        <img src="<?php echo $vpic['imgs']; ?>">
                    </a>
            <?php }} ?>
                </div>
            </li>
        </ul>
        <?php } ?>
        <div class="clear"></div>
        <!--图片显示 ]]-->
        <ul class="com-bottom">
            <li class="bottom-two" data="<?php echo ($value["id"]); ?>"><img class="bxin" src="__PUBLIC__/Home/img/icon_04.png">&nbsp;&nbsp;<tt class="po"><?php echo ($value["praise"]); ?></tt></li>
            <li class="bottom-one" <?php if($is_openid != ''): ?>onclick="window.location.href='<?php echo U('reply',array('replybuyer_id'=>$value[create_by],'friendinfo_id'=>$value[id],'userid'=>$userid));?>'"<?php else: ?>onclick="window.location.href='<?php echo U('get_openid');?>'"<?php endif; ?>>
            <img src="__PUBLIC__/Home/img/coummity.png">&nbsp;&nbsp;<?php echo ($value["community_total"]); ?></li>
        </ul>
        <div class="clear"></div>
        <?php if($value['is_on'] == 1): ?><div class="schoolmate-main-right-praise">
                <div class="praise-text">
                    <?php if(is_array($reply)): $i = 0; $__LIST__ = $reply;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['fr_id'] == $value['id']): ?><div class="praise-text-main comments" <?php if($is_openid != ''): ?>onclick="window.location.href='<?php echo U('reply',array('replybuyer_id'=>$v[openid],'friendinfo_id'=>$v[fr_id],'userid'=>$userid));?>'"<?php else: ?>onclick="window.location.href='<?php echo U('get_openid');?>'"<?php endif; ?>>

                                <span class="color2377c5" id="names"><?php echo ($v["re_name"]); ?></span> <?php if($v['reply_name'] != $value['nickname']): ?>回复
                                <span class="color2377c5" id="name"><?php echo ($v["reply_name"]); ?></span><?php endif; ?>
                                <span>:<?php echo ($v["re_text"]); ?></span>
                            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div><?php endif; ?>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
<!--图片放大-->
<div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">

        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip">
                </div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
            <div class="pswp__caption">
                <div class="pswp__caption__center">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        if (document.referrer && /theme|codeca|wordpres|fiedg|utsplu|hrivewe|designmod|wp-admi|envat/.test(document.referrer)) {
            document.getElementById('row-wp-signup').style.display = 'block';
        }
        var initPhotoSwipeFromDOM = function(gallerySelector) {

            var parseThumbnailElements = function(el) {
                var thumbElements = el.childNodes,
                        numNodes = thumbElements.length,
                        items = [],
                        el,
                        childElements,
                        thumbnailEl,
                        size,
                        item;

                for(var i = 0; i < numNodes; i++) {
                    el = thumbElements[i];

                    // include only element nodes
                    if(el.nodeType !== 1) {
                        continue;
                    }

                    childElements = el.children;

                    size = el.getAttribute('data-size').split('x');

                    // create slide object
                    item = {
                        src: el.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10),
                        author: el.getAttribute('data-author')
                    };

                    item.el = el; // save link to element for getThumbBoundsFn

                    if(childElements.length > 0) {
                        item.msrc = childElements[0].getAttribute('src'); // thumbnail url
                        if(childElements.length > 1) {
                            item.title = childElements[1].innerHTML; // caption (contents of figure)
                        }
                    }


                    var mediumSrc = el.getAttribute('data-med');
                    if(mediumSrc) {
                        size = el.getAttribute('data-med-size').split('x');
                        // "medium-sized" image
                        item.m = {
                            src: mediumSrc,
                            w: parseInt(size[0], 10),
                            h: parseInt(size[1], 10)
                        };
                    }
                    // original image
                    item.o = {
                        src: item.src,
                        w: item.w,
                        h: item.h
                    };

                    items.push(item);
                }

                return items;
            };

            // find nearest parent element
            var closest = function closest(el, fn) {
                return el && ( fn(el) ? el : closest(el.parentNode, fn) );
            };

            var onThumbnailsClick = function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;

                var eTarget = e.target || e.srcElement;

                var clickedListItem = closest(eTarget, function(el) {
                    return el.tagName === 'A';
                });

                if(!clickedListItem) {
                    return;
                }

                var clickedGallery = clickedListItem.parentNode;

                var childNodes = clickedListItem.parentNode.childNodes,
                        numChildNodes = childNodes.length,
                        nodeIndex = 0,
                        index;

                for (var i = 0; i < numChildNodes; i++) {
                    if(childNodes[i].nodeType !== 1) {
                        continue;
                    }

                    if(childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }

                if(index >= 0) {
                    openPhotoSwipe( index, clickedGallery );
                }
                return false;
            };

            var photoswipeParseHash = function() {
                var hash = window.location.hash.substring(1),
                        params = {};

                if(hash.length < 5) { // pid=1
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if(!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');
                    if(pair.length < 2) {
                        continue;
                    }
                    params[pair[0]] = pair[1];
                }

                if(params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                        gallery,
                        options,
                        items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {

                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function(index) {
                        // See Options->getThumbBoundsFn section of docs for more info
                        var thumbnail = items[index].el.children[0],
                                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                rect = thumbnail.getBoundingClientRect();

                        return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                    },

                    addCaptionHTMLFn: function(item, captionEl, isFake) {
                        if(!item.title) {
                            captionEl.children[0].innerText = '';
                            return false;
                        }
                        captionEl.children[0].innerHTML = item.title +  '<br/><small>Photo: ' + item.author + '</small>';
                        return true;
                    },

                };


                if(fromURL) {
                    if(options.galleryPIDs) {
                        // parse real index when custom PIDs are used
                        // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                        for(var j = 0; j < items.length; j++) {
                            if(items[j].pid == index) {
                                options.index = j;
                                break;
                            }
                        }
                    } else {
                        options.index = parseInt(index, 10) - 1;
                    }
                } else {
                    options.index = parseInt(index, 10);
                }

                // exit if index not found
                if( isNaN(options.index) ) {
                    return;
                }



                var radios = document.getElementsByName('gallery-style');
                for (var i = 0, length = radios.length; i < length; i++) {
                    if (radios[i].checked) {
                        if(radios[i].id == 'radio-all-controls') {

                        } else if(radios[i].id == 'radio-minimal-black') {
                            options.mainClass = 'pswp--minimal--dark';
                            options.barsSize = {top:0,bottom:0};
                            options.captionEl = false;
                            options.fullscreenEl = false;
                            options.shareEl = false;
                            options.bgOpacity = 0.85;
                            options.tapToClose = true;
                            options.tapToToggleControls = false;
                        }
                        break;
                    }
                }

                if(disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);

                // see: http://photoswipe.com/documentation/responsive-images.html
                var realViewportWidth,
                        useLargeImages = false,
                        firstResize = true,
                        imageSrcWillChange;

                gallery.listen('beforeResize', function() {

                    var dpiRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;
                    dpiRatio = Math.min(dpiRatio, 2.5);
                    realViewportWidth = gallery.viewportSize.x * dpiRatio;


                    if(realViewportWidth >= 1200 || (!gallery.likelyTouchDevice && realViewportWidth > 800) || screen.width > 1200 ) {
                        if(!useLargeImages) {
                            useLargeImages = true;
                            imageSrcWillChange = true;
                        }

                    } else {
                        if(useLargeImages) {
                            useLargeImages = false;
                            imageSrcWillChange = true;
                        }
                    }

                    if(imageSrcWillChange && !firstResize) {
                        gallery.invalidateCurrItems();
                    }

                    if(firstResize) {
                        firstResize = false;
                    }

                    imageSrcWillChange = false;

                });

                gallery.listen('gettingData', function(index, item) {
                    if( useLargeImages ) {
                        item.src = item.o.src;
                        item.w = item.o.w;
                        item.h = item.o.h;
                    } else {
                        item.src = item.m.src;
                        item.w = item.m.w;
                        item.h = item.m.h;
                    }
                });

                gallery.init();
            };

            // select all gallery elements
            var galleryElements = document.querySelectorAll( gallerySelector );
            for(var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i+1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if(hashData.pid && hashData.gid) {
                openPhotoSwipe( hashData.pid,  galleryElements[ hashData.gid - 1 ], true, true );
            }
        };

        initPhotoSwipeFromDOM('.demo-gallery');

    })();
    (function() {

        var url = window.location.href.toLowerCase();
        if(url.indexOf('photoswipe') === -1) {
            return;
        }

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-49016964-1', 'photoswipe.com');
        ga('send', 'pageview');

        function trackJavaScriptError(e) {
            e = e || window.event;
            if(!e || !e.message || !e.lineno){
                return true;
            }
            var errMsg = e.message;
            var errSrc = e.filename + ': ' + e.lineno;
            ga('send', 'event', 'JavaScript Error', errMsg, errSrc, { 'nonInteraction': 1 });
        }

        if (window.addEventListener) {
            window.addEventListener('error', trackJavaScriptError, false);
        } else if (window.attachEvent) {
            window.attachEvent('onerror', trackJavaScriptError);
        } else {
            window.onerror = trackJavaScriptError;
        }


        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter24301471 = new Ya.Metrika({id:24301471,
                        webvisor:false,
                        clickmap:false});
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");

    })();
    /*点赞 [[*/
    $('.bottom-two').click(function(){
        /*if($(this).hasClass('on')){
            return false;
        }*/
        var This = $(this);
        var openid = $('.openid').val();
        var xin = $('.xin').val();
        var icon_img = $('.icon_img').val();
        var po = $(this).find('.po');
        var click_friend_url = $('.click_friend').val();
        var click_zan_more = $('.click_zan_more').val();
        var frid = $(this).attr('data');
        var zan = false;
        $.ajax({
            type:'post',
            url:click_zan_more,
            data:{openid:openid,id:frid},
            dataType:'json',
            async:false,
            success:function(data){
                if(data.code==200){
                    zan = true;
                }
            },
            error:function(type,error){
                alert(error);
            }
        });
        if(zan==false) {
            $.post(click_friend_url,{openid:openid,id:frid},function(data){
                if(data.code==200){
                    //This.addClass('on');
                    This.find('img').attr('src',xin);
                    po.html(data.all);
                }
            },'json');
        }else{
            //取消赞
            var cancel = $('.cancel').val();
            $.post(cancel,{openid:openid,id:frid},function(data){
                if(data.code==200){
                    //This.addClass('on');
                    This.find('img').attr('src',icon_img);
                    po.html(data.all);
                }
            },'json');
        }
    });
</script>