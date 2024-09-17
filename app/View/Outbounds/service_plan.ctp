<style>
.content ul{
	list-style: none;
	font-size: 10px;
	font-family:'Open Sans';
	color: #9095aa;
	padding: 0px;
	margin: 0px;
}

.content li{
	border-bottom: 1px solid #494a5a;
	padding: 0px;
	margin: 0px;
	text-align: center;
	height: 30px;
	line-height: 30px;
	font-size:12px;
}

#container{
	width: 100%;
  text-align:center;
}

.whole{
	display: inline-block;
  
}

.type{
	width: 150px;
	border-radius: 5px 5px 0px 0px;
	background-color: #eac80d;
	height:30px;
	border-bottom: 1px solid #bfa30c;

}

.type p{
	font-family:'Open Sans';
    font-weight: 200;
	font-size: 20px;
	text-transform: uppercase;
	color: white;
	text-align: center;
	padding-top:5px;
}
.plan{
	width: 150px;
	background-color: #2b2937;
	border-radius: 0px 0px 5px 5px;
    font-family:'Open Sans';
    font-style:condensed;
    font-size: 10px;
    color: white;
    text-align: center;
}
.price{
	height:35px;
}
.cart{
  color:white;
  position: relative;
  top: 5px;
  font-size:18px
}
</style>

<div id="container">
	<?php 
	$planArr=array('PLAN 1','PLAN 2','PLAN 3','PLAN 4','PLAN 5');
	foreach($planArr as $planrow){?>
	<div class="whole">
        <div class="type">
            <p><?php echo $planrow;?></p>
        </div>
		<div class="plan">
            <div class="content">
                <ul>
                	<?php for($j=1;$j<=10;$j++){?>
                    <li>15 Email Accounts</li>
                    <?php }?>
                </ul>
            </div>
            <div class="price">
                <a href="#" class="bottom"><p class="cart">Add to cart</p></a>
            </div>
		</div>
	</div>
    <?php }?>
</div>

