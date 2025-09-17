<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Session::get('user_id')) { $this->redirect('/'); }
        $this->render('pages/auth/login', [
            'csrf' => Session::csrfToken(),
            'error' => Session::flash('error')
        ]);
    }
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        if ($email === '' || $password === '') {
            Session::flash('error', 'Email y contraseña son requeridos.');
            $this->redirect('/login');
        }
        $user = User::where('email', $email)->first();
        if (!$user || !password_verify($password, (string)$user->password)) {
            Session::flash('error', 'Credenciales inválidas.');
            $this->redirect('/login');
        }
        Session::set('user_id', (int)$user->id);
        $this->redirect('/');
    }
    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
