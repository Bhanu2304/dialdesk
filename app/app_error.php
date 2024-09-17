<?php
class AppError extends ErrorHandler {


    function missingController($params) {
        extract($params, EXTR_OVERWRITE);

        $this->controller->redirect();
    }    


}

