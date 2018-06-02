<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PaymentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('auth.account');
    }

    public function changePassword()
    {
        return view('auth.passwords.change');
    }

    public function update(Request $request)
    {
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

    public function updatePassword(Request $request)
    {
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

    public function validar(array $post)
    {
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

    public function validarPassword($post)
    {
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

    public function paymentData()
    {
        return view('auth.paymentdata');
    }

    public function updatePaymentData(Request $request)
    {
        $post = $request->except(['_method', '_token']);
        $validator = $this->validatePaymentData($post);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Auth::user()->paymentData()->exists()) {
                Auth::user()->paymentData()->update($post);
            } else {
                Auth::user()->paymentData()->create($post);
            }
            return redirect()->back()
                ->with('success', 'Dados Bancários atualizados com sucesso.');
        }
    }

    public function validatePaymentData($post)
    {
        $mensagens = array(
            'holder.min' => 'Nome do titular muito curto.',
            'agency.min' => 'Nome da agencia muito curto.' ,
            'number.required' => 'Número da conta necessário.',
            'type.in' => 'Tipo inválido de conta.',
            'bank.min' => 'Nome do banco muito curto.',
            'bak_number.required' => 'Número do banco necessário.',
            'cpf.regex' => 'CPF fora do padrão: 999.999.999-99'
        );
        $rules = array(
            'holder' => 'min:4',
            'agency' => 'min:4',
            'number' => 'required',
            'type' => 'in:1,2',
            'bank' => 'min:4',
            'bank_number' => 'required',
            'cpf' => 'regex:/^\d{3}\.\d{3}.\d{3}\-\d{2}$/'
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }
}
