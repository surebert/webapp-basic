<?php

class TestController extends IndexController{
 
    /**
     * @servable true
     */
    public function eatCake(){
        die('eat');
    }
}
?>