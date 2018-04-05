<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function edit() {
        return view('auth.account');
    }

    public function changePassword() {
        return view('auth.passwords.change');
    }

    public function update(Request $request) {
        $post = $request->only(['name', 'skype', 'phone']);
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            Auth::user()->update($post);
            return back()->with(
                            'success', 'Dados do usuário atualizados com sucesso.');
        }
    }

    public function updatePassword(Request $request) {
        $post = $request->all();
        $validacao = $this->validarPassword($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else if (!Hash::check($post['password'], Auth::user()->password)) {
            return redirect()->back()
                            ->withErrors(['password' => 'Password incorreto.'])
                            ->withInput();
        } else {
            $post['password'] = Hash::make($post['new_password']);
            Auth::user()->update($post);
            return redirect('home')
                            ->with('success'
                                    , 'Senha alterada com sucesso.');
        }
    }

    public function validar(array $post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            'name.min' => 'Nome muito curto.',
            'name.min' => 'Nome muito curto.',
            'name.string' => 'Não é do tipo string.',
//            'email.required' => 'Insira um e-mail.',
//            'email.email' => 'E-mail inválido.',
//            'email.max' => 'E-mail muito longo.',
//            'email.unique' => 'Já existe uma conta com este e-mail.',
//            'email.string' => 'Não é do tipo string.',
        );
        $rules = array(
            'name' => 'required|string|min:4|max:255',
//            'email' => 'required|email|max:255|unique:users',
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function validarPassword($post) {
        $mensagens = array(
            'new_password.confirmed' => 'Senhas não coincidem.',
            'new_password.min' => 'Senha muito curta.',
            'new_password.required' => 'Insira uma senha.',
        );
        $rules = array(
            'new_password' => 'required|min:4|confirmed',
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

}
