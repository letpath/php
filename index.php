<?php

require('.apifunc/apifunc.php');

try {
    // how to load composer packages?
//    let_html([
    apifunc([
        'https://php.parkingomat.com/post.php',
        'https://php.parkingomat.com/header.php',
        'https://php.parkingomat.com/form.php',
        'https://php.parkingomat.com/footer.php',
    ], function () {

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header_json([
                'message' => 'The Request is not GET METHOD',
                'error' => true
            ]);
        }

        $objs = new LetJson("../../plesk.json");
//        header_json((array) $objs);


    }, '../.apifunc');

} catch (Exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    header_json([
        'message' => $e->getMessage(),
        'error' => true
    ]);
}
