<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function showLoginForm()
    {
        if (attempt_auto_login()) {
            redirect('admin/dashboard');
            exit;
        }

        if (is_logged_in()) {
            redirect('admin/dashboard');
            exit;
        }

        $data = [
            "title" => 'Sign In',
        ];

        return view('admin/auth/login', $data);
    }
    public function login()
    {
        if (!is_post()) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        try {
            $userModel = new User();

            $username = request('username');
            $password = request('password');
            $remember = (bool) request('remember_me');

            $user = $userModel->verifyLogin($username, $password);

            if ($user) {
                session('user', $user);
                csrf_token();

                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60);

                    $userModel->updateRememberToken($user->id, hash('sha256', $token), date('Y-m-d H:i:s', $expires));

                    $cookie_value = $user->id . ':' . $token;
                    cookie('remember_me_token', $cookie_value, 30 * 24 * 60 * 60);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau password salah'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function logout()
    {
        if (session('user')) {
            $user_id = session('user')->id;
            $userModel = new User();
            $userModel->updateRememberToken($user_id, null, null);
        }

        clear_session();

        clear_cookie('remember_me_token');

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
