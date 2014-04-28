<?php
/*
|--------------------------------------------------------------------------
| Controlador de la autenticación de usuarios
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con los usuarios
|
*/
class AuthController extends BaseController {
    /**
    * Muestra el formulario de login
    * @return Vista usuarios.login
    */
    public function verLogin() {
        // Verificamos que el usuario no esté autenticado
        if (Auth::check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('/');
        }
        // Mostramos la vista usuarios/login.blade.php
        return View::make('usuarios.login');
    }

    /**
    * Procesa los datos del login
    * @return Si el login ha sido correcto -> Redirección a /
    *         Si el login ha sido erróneo -> Redirección a /login con errores
    */
    public function postLogin() {
        // Guardamos en un arreglo los datos del usuario.
        $datos = array(
            'usuario' => Input::get('usuario'),
            'password'=> Input::get('pass')
        );
        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        if(Auth::attempt($datos, Input::get('recordarme', 0)))
        {
            // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/');
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        return Redirect::to('login')
                    ->with('titulo_error', 'Sus datos son incorrectos')
                    ->with('mensaje_error', 'Compruebe su nombre de usuario y contraseña.')
                    ->withInput();
    }

    /**
    * Desloguea al usuario actual
    * @return Redirección a /login con aviso de éxito
    */
    public function logout() {
        Auth::logout();
        return Redirect::to('login')
                    ->with('titulo_exito', 'Tu sesión ha sido cerrada.')
                    ->with('mensaje_exito', 'Para acceder a la aplicación vuelva a loguearse.');
    }
}