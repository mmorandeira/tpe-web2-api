<?php

namespace Moran\Controller;

require_once('./utils/Encode.php');
require_once('./view/ApiView.php');
require_once('./model/UserModel.php');
require_once('./helpers/ApiAuthHelper.php');


use Moran\View\ApiView;
use Moran\Helpers\ApiAuthHelper;
use Moran\Model\UserModel;

class ApiAuthController
{
    private $model;
    private $view;
    private $authHelper;

    private $data;

    public function __construct()
    {
        //$this->model = new TaskModel();
        $this->view = new ApiView();
        $this->model = new UserModel();
        $this->authHelper = new ApiAuthHelper();

        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData()
    {
        return json_decode($this->data);
    }

    public function getToken($params = null)
    {
        // Obtener "Basic base64(user:pass)
        $basic = $this->authHelper->getAuthHeader();

        if (empty($basic)) {
            $this->view->response('No autorizado', 401);
            return;
        }
        $basic = explode(" ", $basic); // ["Basic" "base64(user:pass)"]
        if ($basic[0] != "Basic") {
            $this->view->response('La autenticación debe ser Basic', 401);
            return;
        }

        //validar usuario:contraseña
        $userpass = base64_decode($basic[1]); // user:pass
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $pass = $userpass[1];

        $user = $this->model->getByEmail($user);

        if (!empty($user) && password_verify($pass, $user->getPassword())) {
            //  crear un token
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => "Nico",
                'exp' => time() + 3600
            );
            $header = \Moran\Utils\base64url_encode(json_encode($header));
            $payload = \Moran\Utils\base64url_encode(json_encode($payload));
            $signature = hash_hmac('SHA256', "$header.$payload", "Clave1234", true);
            $signature = \Moran\Utils\base64url_encode($signature);
            $token = "$header.$payload.$signature";
            $this->view->response(['token' => $token]);
        } else {
            $this->view->response('No autorizado', 401);
        }
    }
}
