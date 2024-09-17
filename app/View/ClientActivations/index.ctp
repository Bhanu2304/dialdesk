<div class="container" id="registration-form">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="landing-page">
                <div class="top-header">
                    <div class="top-left-logo">
                        <a href="http://dialdesk.in/"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
                    </div>
                    <div class="top-right-login">
                        <a href="<?php echo $this->webroot;?>client_activations/company_registration" style="word-spacing: 0px;" >New User</a>
                        <a href="<?php echo $this->webroot;?>client_activations/login" >Login</a>
                    </div>
                </div>
                <div class="landing-slider">
                    <script type="text/javascript" src="<?php echo $this->webroot;?>slider/js/jssor.slider.mini.js"></script>
                    <script>
                        jQuery(document).ready(function ($) {
                            var jssor_1_SlideoTransitions = [
                              [{b:5500,d:3000,o:-1,r:240,e:{r:2}}],
                              [{b:-1,d:1,o:-1,c:{x:51.0,t:-51.0}},{b:0,d:1000,o:1,c:{x:-51.0,t:51.0},e:{o:7,c:{x:7,t:7}}}],
                              [{b:-1,d:1,o:-1,sX:9,sY:9},{b:1000,d:1000,o:1,sX:-9,sY:-9,e:{sX:2,sY:2}}],
                              [{b:-1,d:1,o:-1,r:-180,sX:9,sY:9},{b:2000,d:1000,o:1,r:180,sX:-9,sY:-9,e:{r:2,sX:2,sY:2}}],
                              [{b:-1,d:1,o:-1},{b:3000,d:2000,y:180,o:1,e:{y:16}}],
                              [{b:-1,d:1,o:-1,r:-150},{b:7500,d:1600,o:1,r:150,e:{r:3}}],
                              [{b:10000,d:2000,x:-379,e:{x:7}}],
                              [{b:10000,d:2000,x:-379,e:{x:7}}],
                              [{b:-1,d:1,o:-1,r:288,sX:9,sY:9},{b:9100,d:900,x:-1400,y:-660,o:1,r:-288,sX:-9,sY:-9,e:{r:6}},{b:10000,d:1600,x:-200,o:-1,e:{x:16}}]
                            ];

                            var jssor_1_options = {
                              $AutoPlay: true,
                              $SlideDuration: 800,
                              $SlideEasing: $Jease$.$OutQuint,
                              $CaptionSliderOptions: {
                                $Class: $JssorCaptionSlideo$,
                                $Transitions: jssor_1_SlideoTransitions
                              },
                              $ArrowNavigatorOptions: {
                                $Class: $JssorArrowNavigator$
                              },
                              $BulletNavigatorOptions: {
                                $Class: $JssorBulletNavigator$
                              }
                            };

                            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

                            //responsive code begin
                            //you can remove responsive code if you don't want the slider scales while window resizing
                            function ScaleSlider() {
                                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                                if (refSize) {
                                    refSize = Math.min(refSize, 1920);
                                    jssor_1_slider.$ScaleWidth(refSize);
                                }
                                else {
                                    window.setTimeout(ScaleSlider, 30);
                                }
                            }
                            ScaleSlider();
                            $(window).bind("load", ScaleSlider);
                            $(window).bind("resize", ScaleSlider);
                            $(window).bind("orientationchange", ScaleSlider);
                            //responsive code end
                        });
                    </script>
                    <style>
                        .jssorb05 {
                            position: absolute;
                        }
                        .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
                            position: absolute;
                            /* size of bullet elment */
                            width: 16px;
                            height: 16px;
                            background: url('<?php echo $this->webroot;?>slider/img/b05.png') no-repeat;
                            overflow: hidden;
                            cursor: pointer;
                        }
                        .jssorb05 div { background-position: -7px -7px; }
                        .jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
                        .jssorb05 .av { background-position: -67px -7px; }
                        .jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }

                        .jssora22l, .jssora22r {
                            display: block;
                            position: absolute;
                            /* size of arrow element */
                            width: 40px;
                            height: 58px;
                            cursor: pointer;
                            background: url('<?php echo $this->webroot;?>slider/img/a22.png') center center no-repeat;
                            overflow: hidden;
                        }
                        .jssora22l { background-position: -10px -31px; }
                        .jssora22r { background-position: -70px -31px; }
                        .jssora22l:hover { background-position: -130px -31px; }
                        .jssora22r:hover { background-position: -190px -31px; }
                        .jssora22l.jssora22ldn { background-position: -250px -31px; }
                        .jssora22r.jssora22rdn { background-position: -310px -31px; }

                    </style>
                    
                    <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden; visibility: hidden;">
                        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                            <div style="position:absolute;display:block;background:url('<?php echo $this->webroot;?>slider/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                        </div>
                        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
                            <div data-p="225.00" style="display: none;">
                                <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide01.jpg" />
                            </div>
                            <div data-p="225.00" style="display: none;">
                                <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide02.jpg" />
                            </div>
                            <div data-p="225.00" style="display: none;">
                                <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide03.jpg" />
                            </div>
                            <a data-u="ad" href="http://www.jssor.com" style="display:none">jQuery Slider</a>
                        </div>
                        <div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
                            <div data-u="prototype" style="width:16px;height:16px;"></div>
                        </div>
                        <span data-u="arrowleft" class="jssora22l" style="top:0px;left:12px;width:40px;height:58px;" data-autocenter="2"></span>
                        <span data-u="arrowright" class="jssora22r" style="top:0px;right:12px;width:40px;height:58px;" data-autocenter="2"></span>
                    </div>                            
                </div>

                <div class="row" style="margin-top:20px;">
                    <div class="col-md-4">
                        <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                            <div class="panel-body detail">

                                <div class="tab-content">
                                    <div class="tab-pane active" id="horizontal-form">                             
                                        <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide01.jpg" class="img-setting" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                            <div class="panel-body detail">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="horizontal-form">                             
                                        <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide02.jpg" class="img-setting" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                            <div class="panel-body detail">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="horizontal-form">                             
                                        <img data-u="image" src="<?php echo $this->webroot;?>slider/img/slide03.jpg" class="img-setting" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                
                <div class="down-footer">
                    <ul class="list-unstyled list-inline pull-left">
                        <li><h6 style="padding-top:10px;margin-left: 15px;padding-top: 60px;color:white;">&copy; 2016 DialDesk</h6></li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
</div>