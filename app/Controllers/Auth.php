<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {

                session()->set([
                    'id'        => $user['id'],
                    'nama'      => $user['nama'],
                    'role'      => $user['role'],
                    'logged_in' => true
                ]);

                // ðŸ”¥ FIX redirect (biar aman di ngrok & localhost)
                return redirect()->to(base_url('dashboard'));
            }
        }

        return redirect()->back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        session()->destroy();

        // ðŸ”¥ FIX redirect
        return redirect()->to(base_url('/'));
    }
}
