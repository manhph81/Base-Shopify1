<?php
class Theme
{
    static function changeThemeCartItem()
    {
        $data = shopify_call("themes.json", 'GET');
        $themes = (array)$data['response']->themes;
        $text_search = '
        <!-- AGE LIMIT APP -->
        <!-- sections/main-cart:  onclick="remove(this)"-->
        
        <!-- GET PRODUCTS IN CART -->
        {% assign my_variable =  "" %}
        {% assign my_variants =  "" %}
        {% for item in cart.items %}
        
            {% assign my_variable = item.product.id | append: my_variable %}
            {% assign my_variable =  " " | append: my_variable %}
        
            {% assign my_variants = item.url | append: my_variants %}
            {% assign my_variants =  " " | append: my_variants %}
        
        {% endfor %}

        
        
        
        <!-- GET OLD -->
        {% for tag  in customer.tags %}
            {% assign size = tag | size %}
            {% if size == 10   %}
                {% assign check = tag | slice: 4 %}
                {% assign dob = tag %}
            
                {% if  check == "-"  %}
                    {% assign now = "now" | date: "%Y-%m-%d" %}
                    {% assign old =   now | minus: tag  %}
                {% endif %}
            {% endif %}
        {% endfor %}
    
    
        <div id = "dip" style="float: left; margin-top:40px;width: 50%;">
        <div style="float:left;">
            <input   id="checkboxDip" type="checkbox" value = "" name="checkboxDip">
        </div>
        <div style="margin-left: 3%;"> <p style="white-space: pre-wrap;margin-top: 0; " id="textRuleDip"></p></div>

        </div>
       
         
        <!-- The Modal -->
        <div id="modalLoading" class="modal-new">
            <!-- Modal content -->
            <div class="modal-content">
            <div class="center">
                読み込み中......
            </div>
            </div>
        </div>
  
        <div id="myModal" class="modal-new">
            <!-- Modal content -->
            <div class="modal-content" >
                <span id="closeMyModal" class="disableHover">&times;</span>
            
                <div style="position:relative">
                  <div class="slideshow-container" id="slideshow-container"></div>
                   <a class="prev" id="prev" onclick="plusSlides(-1)" style="
                      position: absolute;
                      top: 50%;
                      left: 0;
                      transform: translateY(-50%);
                      margin-top: 0px;
                  ">&#10094;</a>
                  <a class="next"  id="next" onclick="plusSlides(1)" style="
                      margin-top: 0px;
                      position: absolute;
                      top: 50%;
                      right: 0;
                      transform: translateY(-50%);
                  ">&#10095;</a>
                  
                </div>
               
                <br>
                <div style="text-align:center" id="slide-dot"> 
                </div>
            
                <br>
                <div style="float:left;">
                    <input   id="my_variable" type="checkbox" value = "" name="my_variable">
                </div>
                <div style="margin-left: 3%;"> <p style="white-space: pre-wrap;margin-top: 0;" id="textRule"></p></div>

                <br>
                <div class="center">
                    <button style="width:10%;" type="button" onclick="hiddenModal()" id="customer_login_link">OK</button>
                </div>
                <br>
        
            </div>
        </div>
    
        <div id="modalLogout" class="modal-new">
            <!-- Modal content -->
            <div class="modal-content">
                <br>
                <div class="center" >
                    <p style="white-space: pre-wrap;">{{ \'customer.register.a\' | t }}</p>
                    <br>
                    <a onclick="setCookie(\'cart_sig\',\'\',0);setCookie(\'cart_sig\',\'\',0);" href="/account/login" id="customer_login_link">{{ \'customer.register.b\' | t }}</a>
                </div>
                <br>
            </div>
        </div>
    
        <div id="modalAddOld" class="modal-new">
            <!-- Modal content -->
            <div class="modal-content">
                <span id="closeModalAddOld" class="close">&times;</span>
                <br>
                <div class="center">
                    {{ \'customer.register.c\' | t }}
                    <br>
                    <br>
                </div>
            </div>
        </div>
    
        <div id="ageError" class="modal-new">
            <!-- Modal content -->
            <div class="modal-content">
                <span id="closeModalAgeError" class="close">&times;</span>
                <br>
                <div class="center">
                    この商品は <span id="max_age"></span>  歳未満の購入が禁じられています。 
                    <br>
                    <br>
            
                    <br>
                </div>
            </div>
        </div>
    
        <script id="Popup" type="text/javascript">
            document.addEventListener( \'DOMContentLoaded\', function () {
                // show the alert
                setTimeout(function() {
                    document.getElementById(\'modalLoading\').style.display = "block";
                }, 0);
                setTimeout(function() {
                    document.getElementById(\'modalLoading\').style.display = "none";
                }, 1000);
        
            }, false );
      

        var list = "{{my_variable}}";
        var listvar = "{{my_variants}}";

        // Get the modal
        var modal = document.getElementById(\'myModal\');

        // Get the <span> element that closes the modal
        var span = document.getElementById(\'closeMyModal\');
  
        if(document.getElementById(\'dip\')){
            var dip = document.getElementById(\'dip\');
        }
  
        if(document.getElementById(\'my_variable\')){
            var id = document.getElementById(\'my_variable\').value;
        }

        function remove(obj) {
            var href = obj.href;
            var id = href.substring(href.indexOf("id=")+3, href.lastIndexOf(":"));
            listvar = listvar.replace(id,"");
            hiddenCheckout();
            activeCheckout();
        }  

        function setCookie(cname, cvalue, exMins) {
            var d = new Date();
            d.setTime(d.getTime() + (exMins*60*100));
            var expires = "expires="+d.toUTCString();  
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function isShowModal(){
        
            if(document.getElementById(\'my_variable\').checked || document.getElementById(\'checkboxDip\').checked){
                return true;
            }else{
                return showModal();
            }
        }
      
        function showModal(){

            // When the user clicks on the button, open the modal 
            var cusId = "{{customer.id}}";
            var old = "{{old}}";
    
            if(cusId==""){
                document.getElementById(\'modalLogout\').style.display = "block";
                document.getElementById("cart").setAttribute("onsubmit","return isShowModal();");
                document.getElementById("cart").classList.add("isPause");
                activeCheckout();
            }else{
                if(old==""){
                    document.getElementById(\'modalAddOld\').style.display = "block";
                    document.getElementById("cart").setAttribute("onsubmit","return isShowModal();");
                    document.getElementById("cart").classList.add("isPause");
                    activeCheckout();
                }else{
                    
                    var xhttp = new XMLHttpRequest();
        
                    xhttp.onload = function() {
                      	if(this.responseText!=""){
                            var rule = this.responseText.split("~");
                            var str = rule[0].split(",");
                            var pro = rule[1].split("  ");
                
                            var ageLimit = str[2];
                            if(pro.length<2){
                                document.getElementById(\'slide-dot\').style.display = "none";
                                document.getElementById(\'next\').style.display = "none";
                                document.getElementById(\'prev\').style.display = "none";

                            }else{
                                document.getElementById(\'slide-dot\').style.display = "block";
                                document.getElementById(\'next\').style.display = "block";
                                document.getElementById(\'prev\').style.display = "block";
                            }

                            if(pro[0] != ""){
                                if(old < ageLimit ){
                                    document.getElementById(\'max_age\').innerHTML = ageLimit;
                                    document.getElementById(\'ageError\').style.display = "block";
                                    document.getElementById("cart").setAttribute("onsubmit","return isShowModal();");
                                    document.getElementById("cart").classList.add("isPause");
                                    activeCheckout();
                                }else{
                                    if(!document.getElementById(\'my_variable\').checked){
                                        document.getElementById(\'customer_login_link\').setAttribute("disabled", "disabled");
                                        document.getElementById(\'customer_login_link\').style.backgroundColor = "#6699FF";
                                    }else{
                                        document.getElementById(\'customer_login_link\').removeAttribute("disabled", "disabled");
                                        document.getElementById(\'customer_login_link\').style.backgroundColor = "#2C6ECB";
        
                                    }
                                    
        
                                    var stradd = str[0];
                                    document.getElementById(\'textRule\').innerHTML=stradd;
                                    document.getElementById(\'textRuleDip\').innerHTML=stradd;
        
                                    document.getElementById(\'my_variable\').value=str[1];
                                    document.getElementById(\'checkboxDip\').value=str[1];
        
                                    var res="";
                                    var dot="";
        
                                    for (let index = 0; index < pro.length; index++) {
                    
                                        var p = pro[index].split(",");
                                        res+=\'<div class="mySlides fade"><div class="text">\'+p[1]+\'</div><br><br> <div class="numbertext">\'+ (index+1) +\'/\' + pro.length +\'</div><br><img style="width:100%; height: 200px; max-width: 200px; max-height:200px; object-fit: contain;" src="\'+p[2]+\'" ></div>\';
                        
                                        dot+=\'<span class="dot" onclick="currentSlide(\'+ (index+1) +\')"></span> \';
                                    }
                    
                                    document.getElementById(\'slideshow-container\').innerHTML=res;
                                    document.getElementById(\'slide-dot\').innerHTML=dot;
                                    showSlides(slideIndex);
                    
                                    if(str[1]==1){
                                        modal.style.display = "block";
                                        document.getElementById("cart").setAttribute("onsubmit","return isShowModal();");
                                        document.getElementById("cart").classList.add("isPause");
                                        activeCheckout();
                                    }else if(str[1]==2){
                                        dip.style.display = "block";
                                        disabledCheckout();
                                        document.getElementById("cart").setAttribute("onsubmit","return isShowModal();");
                                        document.getElementById("cart").classList.add("isPause");
                                    }else{
                                        disabledClick();
                                        activeCheckout();
                                    }
                                } 
                            }else{
                                disabledClick();
                                activeCheckout();
                            }
                        }else{
                            disabledClick();
                            activeCheckout();
                        }             
                        
                    }
                    xhttp.open("GET", "' . $_ENV["HOST"] . '/info?id="+list+"&variants="+listvar, true);
                    xhttp.send();
            	}
            }
            return false;
            
        }
        
        isShowModal();

        function disabledCheckout(){
            var x =document.getElementsByName("checkout");
            for (i = 0; i < x.length; i++) {
              if (x[i].type == "submit") {
                if(!document.getElementById(\'checkboxDip\').checked){
                  x[i].style.backgroundColor = "#C0C0C0" ;
                  x[i].style.boxShadow  = "none";
                }else{
                  x[i].style.backgroundColor = "#121212" ;
                  x[i].style.boxShadow  = "none";
                }

              }
            }
        }

        function activeCheckout(){
            dip.style.display = "none";
            var x =document.getElementsByName("checkout");
            for (i = 0; i < x.length; i++) {
              if (x[i].type == "submit") {
                x[i].style.backgroundColor = "#121212" ;
                x[i].style.boxShadow  = "none";

              }
            }
        }
         
        function disabledClick(){
            document.getElementById("cart").removeAttribute("onsubmit");
            var x =document.getElementsByName("checkout");
            var y = document.getElementById("cart");

            if (y.classList.contains("isPause")) {
              for (i = 0; i < x.length; i++) {
                if (x[i].type == "submit") {
                  x[i].click();

                }
              }
            }
        }
        
    
        function hiddenModal(){
            if(document.getElementById(\'my_variable\').checked || document.getElementById(\'checkboxDip\').checked){
                modal.style.display = "none";
            }
        }
  
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            if(document.getElementById(\'my_variable\').checked || document.getElementById(\'checkboxDip\').checked){
                modal.style.display = "none";
            }
        }
    
    
        document.getElementById(\'closeModalAddOld\').onclick = function() {
            document.getElementById(\'modalAddOld\').style.display = "none";
        }
    
        document.getElementById(\'closeModalAgeError\').onclick = function() {
            document.getElementById(\'ageError\').style.display = "none";
        }
  
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                if(document.getElementById(\'my_variable\').checked || document.getElementById(\'checkboxDip\').checked){
                    modal.style.display = "none";
                }
            }
            if (event.target == document.getElementById(\'ageError\')) {
                document.getElementById(\'ageError\').style.display = "none";
            }
            if (event.target == document.getElementById(\'modalAddOld\')) {
                document.getElementById(\'modalAddOld\').style.display = "none";
            }
        }
        </script>
    
        
        <script id="Checkbox" type="text/javascript">

            function disableHover(){
                var btnclose = document.getElementById(\'closeMyModal\');
                if(document.getElementById(\'my_variable\').checked){
                    btnclose.classList.add(\'close\');
                    btnclose.classList.remove(\'disableHover\');
                }else{
                    btnclose.classList.add(\'disableHover\');
                    btnclose.classList.remove(\'close\');
                } 
            }
        
            function hiddenCheckout() {
                // var x =document.getElementsByName("checkout");
                // for (i = 0; i < x.length; i++) {
                //     if (x[i].type == "submit") {
                //         if(document.getElementById(\'my_variable\').checked || document.getElementById(\'checkboxDip\').checked){
                //             x[i].style.display = "block";
                //         }else{
                //             x[i].style.display = "none";
                //         }
        
                //     }
                // }
                if(document.getElementById("cart-errors")){
                    document.getElementById("cart-errors").hidden  = true;
                }
            }
    
            var obj = document.getElementById(\'my_variable\');
            var obj2 = document.getElementById(\'checkboxDip\');
            obj.addEventListener(\'click\', function (e) {
                if(obj.checked==true){
                    var params = "id={{customer.id}}&checked=1&dob={{dob}}";
                    document.getElementById(\'customer_login_link\').removeAttribute("disabled", "disabled");
                    document.getElementById(\'customer_login_link\').style.backgroundColor = "#2C6ECB";
                    hiddenCheckout();
                    disableHover();
                }else{
                    var params = "id={{customer.id}}&checked=0&dob={{dob}}";
                    document.getElementById(\'customer_login_link\').setAttribute("disabled", "disabled");
                    document.getElementById(\'customer_login_link\').style.backgroundColor = "#6699FF";
                    hiddenCheckout();
                    disableHover();
                }
                var xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    //          console.log(this.responseText);        
            
                }
                xhttp.open("PUT", "' . $_ENV["HOST"] . '/users?"+params, true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
                xhttp.send();
            });
        
            obj2.addEventListener(\'click\', function (e) {
                if(obj2.checked==true){
                    var params = "id={{customer.id}}&checked=1&dob={{dob}}";
                    hiddenCheckout();
                    disabledCheckout();
                    
                }else{
                    var params = "id={{customer.id}}&checked=0&dob={{dob}}";
                    hiddenCheckout();
                    disabledCheckout();
                }
                var xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    //          console.log(this.responseText);        
            
                }
                xhttp.open("PUT", "' . $_ENV["HOST"] . '/users?"+params, true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
                xhttp.send();
            });

        </script>
        
        <script>
            var slideIndex = 1;
        
        
            function plusSlides(n) {
                showSlides(slideIndex += n);
            }
        
            function currentSlide(n) {
                showSlides(slideIndex = n);
            }
        
            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("mySlides");
                var dots = document.getElementsByClassName("dot");
                if (n > slides.length) {slideIndex = 1}    
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";  
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex-1].style.display = "block";  
                dots[slideIndex-1].className += " active";
            }
        </script>
        
        <style>
        
            * {box-sizing: border-box}
        
            .mySlides {display: none}
            img {vertical-align: middle;}
        
            /* Slideshow container */
            .slideshow-container {
                max-width: 1000px;
                position: relative;
                margin: auto;
                text-align:center;
            }
        
            /* Next & previous buttons */
            .prev, .next {
                cursor: pointer;
                position: absolute;
                width: auto;
                padding: 16px;
                margin-top: -10%;
                color: black;
                font-weight: bold;
                font-size: 18px;
                transition: 0.6s ease;
                border-radius: 0 3px 3px 0;
                user-select: none;
            }
        
            /* Position the "next button" to the right */
            .next {
                right: 29%;
                border-radius: 3px 0 0 3px;
            }
        
            /* On hover, add a black background color with a little bit see-through */
            .prev:hover, .next:hover {
                background-color: rgba(0,0,0,0.8);
            }
        
            /* Caption text */
            .text {
                color: black;
                font-size: 16px;
                font-weight: bold;
                padding: 8px 12px;
                position: absolute;
                top: 1%;
                width: 100%;
                text-align: center;
            }
        
            /* Number text (1/3 etc) */
            .numbertext {
                color: #f2f2f2;
                font-size: 12px;
                padding: 8px 12px;
                position: absolute;
                top: 0;
            }
        
            /* The dots/bullets/indicators */
            .dot {
                cursor: pointer;
                height: 15px;
                width: 15px;
                margin: 0 2px;
                background-color: #bbb;
                border-radius: 50%;
                display: inline-block;
                transition: background-color 0.6s ease;
            }
        
            .active, .dot:hover {
                background-color: #717171;
            }
        
            /* Fading animation */
            .fade {
                -webkit-animation-name: fade;
                -webkit-animation-duration: 1.5s;
                animation-name: fade;
                animation-duration: 1.5s;
            }
        
            @-webkit-keyframes fade {
                from {opacity: .4} 
                to {opacity: 1}
            }
        
            @keyframes fade {
                from {opacity: .4} 
                to {opacity: 1}
            }
        
            /* On smaller screens, decrease text size */
            @media only screen and (max-width: 300px) {
            .prev, .next,.text {font-size: 11px}
            }
            /* The Modal (background) */
            .modal-new {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 100000; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto;
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
        
            #customer_login_link{
                padding: 10px;
                background-color: #2C6ECB;
                color: white;
                border: 0 solid white;
                border-radius: 10px;
                text-decoration: none;

            }
        
            #modalAddOld{
                display: none;
            }
        
            #ageError{
                display: none;
            }
        
        
            #modalLogout{
                display: none;
            }
        
            #dip{
                display: none;
            }
        
            /* Modal Content/Box */
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto; /* 15% from the top and centered */
                padding: 20px;
                border: 1px solid #888;
                width: 80%; /* Could be more or less, depending on screen size */
                max-width:600px;
                border-radius: 10px;
            }
            .modal_heading {
                margin-top:10px;
                margin-bottom:10px;
                font-size: 20px;
            }

            .disableHover {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                margin-top: -5%;
            }
             
            .disableHover:hover,
            .disableHover:focus 
            {
                color: #aaa;
                cursor: pointer;
                text-decoration: none;
            }

            /* The Close Button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                margin-top: -5%;
            }
        
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            .modal_special {margin-top:10px;}
            .center {text-align:center;}
        </style>
        
        <!-- END AGE LIMIT APP -->
      
        ';

        foreach ($themes as $item) {
            $themes_id = $item->id;
            Theme::changeThemeCartItemDawn($themes_id, $text_search);
            Theme::changeThemeCartTemplate($themes_id, $text_search);
        }
    }

    static function changeThemeCartItemDawn($themes_id, $text_search)
    {
        $file_name = "sections/main-cart-items.liquid";
        //get content
        $asset = shopify_call("themes/" . $themes_id . "/assets.json?asset[key]=" . $file_name, 'GET');
        if (isset($asset)) {
            $text = $asset['response']->asset->value;
        }

        $text_replace = '</form>';

        $content_change = str_replace($text_replace, $text_search . $text_replace, $text);

        if (strpos($text, $text_search) === false) {
            //change file theme
            $asset_file = array(
                "asset" => array(
                    "key" =>  $file_name,
                    "value" => $content_change
                )
            );
            $asset_change = shopify_call("themes/" . $themes_id . "/assets.json", 'PUT', $asset_file);
        }
    }
    static function changeThemeCartTemplate($themes_id, $text_search)
    {
        $file_name = "sections/cart-template.liquid";
        //get content
        $asset = shopify_call("themes/" . $themes_id . "/assets.json?asset[key]=" . $file_name, 'GET');
        if (isset($asset)) {
            $text = $asset['response']->asset->value;
        }

        $text_replace = '</form>';

        $content_change = str_replace($text_replace, $text_search . $text_replace, $text);

        if (strpos($text, $text_search) === false) {
            //change file theme
            $asset_file = array(
                "asset" => array(
                    "key" =>  $file_name,
                    "value" => $content_change
                )
            );
            $asset_change = shopify_call("themes/" . $themes_id . "/assets.json", 'PUT', $asset_file);
        }
    }

    static function changeThemeEn()
    {
        $data = shopify_call("themes.json", 'GET');
        $themes = (array)$data['response']->themes;

        foreach ($themes as $item) {
            $file_name = "locales/en.default.json";
            $themes_id = $item->id;

            //get content
            $asset = shopify_call("themes/" . $themes_id . "/assets.json?asset[key]=" . $file_name, 'GET');
            $text = $asset['response']->asset->value;

            $text_replace =  '"submit": "Create"';
            $text_search = $text_replace . ',
            "birthday": "Birthday",
            "a": "You must be logged in to purchase items. \n Click the button below to log in.",
            "b": "Login",
            "c": "On this site, you need to enter your date of birth when purchasing the product.\n Please contact the administrator separately."';

            $content_change = preg_replace('/' . $text_replace . '/', $text_search, $text, 1);

            if (strpos($text, $text_search) === false) {
                //change file theme
                $asset_file = array(
                    "asset" => array(
                        "key" =>  $file_name,
                        "value" => $content_change
                    )
                );
                $asset_change = shopify_call("themes/" . $themes_id . "/assets.json", 'PUT', $asset_file);
            }
        }
    }

    static function changeThemeJP()
    {
        $data = shopify_call("themes.json", 'GET');
        $themes = (array)$data['response']->themes;

        foreach ($themes as $item) {
            $file_name = "locales/ja.json";
            $themes_id = $item->id;

            //get content
            $asset = shopify_call("themes/" . $themes_id . "/assets.json?asset[key]=" . $file_name, 'GET');
            $text = $asset['response']->asset->value;

            $text_replace =  '"submit": "作成する"';
            $text_search = $text_replace . ',
            "birthday": "生年月日",
            "a": "品を購入するには、ログインが必要です。\n 以下のボタンをクリックしてログインを行ってください。",
            "b": "ログイン",
            "c": "本サイトでは商品購入時に生年月日を入れていただく必要がございます。\n 別途管理者へのご連絡をお願いいたします。"';

            $content_change = preg_replace('/' . $text_replace . '/', $text_search, $text, 1);


            if (strpos($text, $text_search) === false) {
                //change file theme
                $asset_file = array(
                    "asset" => array(
                        "key" =>  $file_name,
                        "value" => $content_change
                    )
                );
                $asset_change = shopify_call("themes/" . $themes_id . "/assets.json", 'PUT', $asset_file);
            }
        }
    }

    static function changeThemeRegister()
    {
        $data = shopify_call("themes.json", 'GET');
        $themes = (array)$data['response']->themes;

        foreach ($themes as $item) {
            $file_name = "templates/customers/register.liquid";
            $themes_id = $item->id;

            //get content
            $asset = shopify_call("themes/" . $themes_id . "/assets.json?asset[key]=" . $file_name, 'GET');
            $text = $asset['response']->asset->value;

            if (strpos($text, '{%- endform -%}') === false) {
                $text_replace = '{% endform %}';
                $text_search = ' 
                <!-- AGE LIMIT APP -->
                <div class="field"> 

                <!--             <label for="birthday"> -->
                <!--               {{ \'customer.register.birthday\' | t }} -->
                <!--             </label> -->
        
                    <input
                        type="date"
                        name="customer[tags]"
                        id="birthday"
                        required
                        max={{"now" | date: "%Y-%m-%d"}}
                    >
                    
                </div>
                <!-- END AGE LIMIT APP -->
                ' . $text_replace;
            } else {
                $text_replace = '{%- endform -%}';
                $text_search = '
                <!-- AGE LIMIT APP -->
                <div class="field">  
                
                <!--             <label for="birthday"> -->
                <!--               {{ \'customer.register.birthday\' | t }} -->
                <!--             </label> -->
        
                    <input
                        type="date"
                        name="customer[tags]"
                        id="birthday"
                        required
                        max={{"now" | date: "%Y-%m-%d"}}
                    >
                    
                </div>
                <!-- END AGE LIMIT APP -->
                ' . $text_replace;
            }

            $content_change = preg_replace('/' . $text_replace . '/', $text_search, $text, 1);


            if (strpos($text, $text_search) === false) {
                //change file theme
                $asset_file = array(
                    "asset" => array(
                        "key" =>  $file_name,
                        "value" => $content_change
                    )
                );
                $asset_change = shopify_call("themes/" . $themes_id . "/assets.json", 'PUT', $asset_file);
            }
        }
    }
}
