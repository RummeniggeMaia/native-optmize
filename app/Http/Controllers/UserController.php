<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Payment;
use App\UserCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::where('id', '!=', Auth::id())
                        ->orderBy('name', 'asc')->paginate(5);
        return view('users.index', compact('users'));
    }

    public function indexDataTable() {
        $users = User::all();
        return Datatables::of($users)->addColumn('credits', function($campaingn) {
                    return view('comum.button_credits', [
                        'id' => $campaingn->id,
                        'route' => 'users.add_credits'
                    ]);
                })->addColumn('edit', function($user) {
                    return view('comum.button_edit', [
                        'id' => $user->id,
                        'route' => 'users.edit'
                    ]);
                })->addColumn('show', function($user) {
                    return view('comum.button_show', [
                        'id' => $user->id,
                        'route' => 'users.show'
                    ]);
                })->addColumn('delete', function($user) {
                    return view('comum.button_delete', [
                        'id' => $user->id,
                        'route' => 'users.destroy'
                    ]);
                })->editColumn('roles', function($user) {
                    $roles = "";
                    if ($user->hasRole('admin')) {
                        $roles = "Administrador";
                    } else if ($user->hasRole('publi')) {
                        $roles = "Publisher";
                    } else if ($user->hasRole('adver')) {
                        $roles = "Advertiser";
                    }
                    return view('comum.user_roles', [
                        'roles' => $roles
                    ]);
                })->editColumn('status', function($user) {
                    if ($user->status) {
                        return view('comum.status_on');
                    } else {
                        return view('comum.status_off');
                    }
                })->editColumn('revenue', function($user) {
                    if ($user->hasRole('adver')) {
                        return 'R$ ' . $user->revenue_adv;
                    } else {
                        return 'R$ ' . $user->revenue;
                    }
                })->rawColumns(
                        ['edit', 'show', 'delete', 'roles', 'status', 'credits']
                )->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
            $post['taxa'] = $post['taxa'] * 0.01;
            $post['password'] = Hash::make($post['password']);
            $user = User::create($post);
            $roleName = $post['role'] == 2 ? 'adver' : 'publi';
            $role = Role::where('name', $roleName)->first();
            $user->roles()->sync([$role->id]);

            return redirect('users')->with('success'
                                    , 'Usuário cadastrado com sucesso.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);
        return view('users.update', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $post = $request->all();
        $user = User::find($id);
        if (!$user) {
            return redirect('users')->with('error', 'Usuário inexistente.');
        }
        $v = $this->validar($post, true, $user->hasRole('adver'));
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
            try {
                DB::beginTransaction();
                $post['taxa'] = $post['taxa'] * 0.01;
                if ($post['password']) {
                    $post['password'] = Hash::make($post['password']);
                } else {
                    unset($post['password']);
                }
                $user->update($post);
                $roleName = $post['role'] == 2 ? 'adver' : 'publi';
                $role = Role::where('name', $roleName)->first();
                $user->roles()->sync([$role->id]);
                DB::commit();
                return redirect()->back()->with('success'
                                        , 'Usuário atualizado com sucesso.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error'
                    , 'Usuário não foi atualizado.');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        User::find($id)->delete();
        return redirect('users')->with('success'
                                    , 'Usuário excluído com sucesso.');
    }

    public function addCredits($id) {
        $user = User::find($id);
        return view('users.add_credits', compact('user'));
    }

    public function applyCredits(Request $request, $id) {
        $post = $request->only('revenue_adv');
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Usuário inexistente.');
        } else {
            $rules = [
                'revenue_adv' => 'required|numeric|min:1'
            ];
            $msgs = [
                'revenue_adv.required' => 'Insira o valor.',
                'revenue_adv.numeric' => 'Valor deve ser numérico.',
                'revenue_adv.min' => 'Valor muito baixo.',
            ];
            $v = Validator::make($post, $rules, $msgs);
            if ($v->fails()) {
                return redirect()->back()
                                ->withErrors($v)
                                ->withInput();
            } else {
                try {
                    DB::beginTransaction();
                    $user->increment('revenue_adv', $post['revenue_adv']);
                    UserCredit::create([
                        'value' => $post['revenue_adv'],
                        'user_id' => $user->id
                    ]);
                    DB::commit();
                    return redirect()->back()->with('success'
                                            , 'Créditos atribuídos com sucesso.');
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('success'
                                            , 'Créditos atribuídos com sucesso.');
                }
            }
        }
    }

    public function validar($post, $update = false, $isAdver = false) {
        $mensagens = array(
            'name.required' => 'Insira o nome.',
            'name.min' => 'Nome muito curto.',
            'email.required' => 'Insira um e-mail.',
            'email.regex' => 'E-mail inválido.',
            'password.required' => 'Insira o password.',
            'password.min' => 'Password deve ter entre 6 e 10 caracters.',
            'password.max' => 'Password deve ter entre 6 e 10 caracters.',
            'password.regex' => 'Password inválido. Deve conter ao menos uma letra e um número.',
            'status.in' => 'Status inválido.',
            'revenue_adv' => 'Valor não numérico.',
            'role.in' => 'Perfil de usuário inválido'
        );
        $rules = array(
            'name' => 'required|min:4',
            'status' => 'in:0,1',
            'role' => 'in:1,2',
        );
        if (!$update) {
            $rules['password'] = 'required|min:6|max:10|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/';
            $rules['email'] = array(
                'required',
                'regex:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
            );
        }
        if ($isAdver) {
            // $rules['revenue_adv'] = 'numeric';
        }
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }
}
