<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

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
        return Datatables::of($users)->addColumn('edit', function($user) {
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
                        $roles = "Advertiser";
                    } else if ($user->hasRole('user')) {
                        $roles = "Publisher";
                    }
                    return view('comum.user_roles', [
                        'roles' => $roles
                    ]);
                })->editColumn('status', function($user) {
                    if ($user->status) {
                        return view('comum.user_status_on');
                    } else {
                        return view('comum.user_status_off');
                    }
                })->rawColumns(
                        ['edit', 'show', 'delete', 'roles', 'status']
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
            $role = Role::where('name', 'user')->first();
            $user = User::create($post);
            $user->roles()->sync([$role->id]);
//            $user->roles()->attach(Role::where('name', 'user')->first());

            return redirect('users');
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
        $v = $this->validar($post, true);
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
            $post['taxa'] = $post['taxa'] * 0.01;
            if ($post['password']) {
                $post['password'] = Hash::make($post['password']);
            } else {
                unset($post['password']);
            }
            $user = User::find($id);
            $user->update($post);
            return redirect('users');
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
        return redirect('users');
    }

    public function payment(Request $request, $id) {
        
    }
    
    private function validar($post, $update = false) {
        $mensagens = array(
            'name.required' => 'Insira o nome.',
            'name.min' => 'Nome muito curto.',
            'email.required' => 'Insira um e-mail.',
            'email.regex' => 'E-mail inválido.',
            'password.required' => 'Insira o password.',
            'password.min' => 'Password deve ter entre 6 e 10 caracters.',
            'password.max' => 'Password deve ter entre 6 e 10 caracters.',
            'password.regex' => 'Password inválido. Deve conter ao menos uma letra e um número.',
            'status.in' => 'Status inválido.'
        );
        $rules = array(
            'name' => 'required|min:4',
            'email' => array(
                'required',
                'regex:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
            ),
            'status' => 'in:0,1'
        );
        if (!$update) {
            $rules['password'] = 'required|min:6|max:10|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/';
        }
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

}
