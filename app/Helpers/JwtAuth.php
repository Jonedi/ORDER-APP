<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth {

    public $key;

    public function __construct()
    {
        $this->key = 'Esta_es_una_llave_que_solo_conozco_yo_258458';
    }

    public function signup($email, $password, $getToken = null)
    {
        // Verificar el usuario
        $user = User::Where([
            'email'     => $email,
            'password'  => $password
        ])->first();

        $signup = false;

        // Comprobar credenciales
        if (is_object($user)) {
            $signup = true;
        }

        // Generar token
        if ($signup) {
            $token = [
                'sub'   => $user->id,
                'email' => $user->email,
                'name'  => $user->name,
                'iat'   => time(),
                'exp'   => time() + (7 * 24 * 60)
            ];

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }

        } else {
            $data = [
                'status'    => 'error',
                'code'      => 404,
                'message'   => 'Login Incorrecto'
            ];
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}
