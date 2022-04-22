<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Registros;

class RegistrosController extends Controller
{
    //
    public function criarRegistro(Request $request)
    {
        $array = [ 'error' => ''];
        // VALIDAÇÃO:
        $rules = [
            'nome' => 'required|min:5',
            'email'=> 'required|min:9|email',
            'senha'=> 'required|min:9',
            'telefone'=> 'numeric|required|min:5',
            'foto' => 'mimes:jpg'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $array['errorBoolean'] = true;
            $array['error'] = $validator->messages();
            return $array;
        }

        //Inserir mais validação do upload
        if ($request->hasFile('foto')) {
            if ($request->file('foto')->isValid()) {
                $extencao = $request->file('foto')->extension();
                if ($extencao != 'jpg') {
                    $array['errorBoolean'] = true;
                    $array['error'] = [ 'foto' => 'Arquivo com extenção diferente de JPG'];
                    return $array;
                } else {
                    $foto = $request->file('foto')->store('public');
                }
            } else {
                $array['errorBoolean'] = true;
                $array['error'] = [ 'foto' => 'Arquivo invalido'];
                return $array;
            }
        } else {
            $array['errorBoolean'] = true;
            $array['error'] = [ 'foto' => 'Não foi enviado arquivo nenhum'];
            return $array;
        }
        $nome = $request->input('nome');
        $email =  $request->input('email');
        $senha = Hash::make($request->input('senha'));
        $telefone =  $request->input('telefone');
        $url =  asset(Storage::url($foto));

        $registro = new Registros();
        $registro->nome = $nome;
        $registro->email = $email;
        $registro->senha = $senha;
        $registro->telefone = $telefone;
        $registro->foto= $url;

        
        
        $verificaSeExisteEmail = $this->verificaEmail($email);
        
        if ($verificaSeExisteEmail == true) {
            $registro->save();
            $verificaSeFoiInserido =  $this->verificaEmail($email);
            if ($verificaSeFoiInserido == false) {
                $array['mensagem']= 'Registro inserido com sucesso!!!';
                return $array;
            } else {
                $array['errorBoolean'] = true;
                $array['error'] = [ 'emailInvalido' =>'Registro não inserido!!!'];
                return  $array;
            }
        } else {
            $array['errorBoolean'] = true;
            $array['error'] = [ 'emailInvalido' => 'Registro com esse EMAIL já existe!!!'];
            return json_encode($array);
        }
    }
    
    public function alterarRegistro(Request $request)
    {
        $registro = Registros::find($request->input('id'));
        if ($registro) {
            $array = [ 'error' => ''];
            // VALIDAÇÃO:
            $verificao = [
                'nome' => 'required|min:5',
                'email'=> 'required|min:9|email',
                'senha'=> 'required|min:9',
                'telefone'=> 'numeric|required|min:5',
                
            ];
            $validador = Validator::make($request->all(), $verificao);
            if ($validador->fails()) {
                $array['errorBoolean'] = true;
                $array['error'] = $validador->messages();
                return $array;
            }

            //Inserir mais validação do upload
            if ($request->hasFile('foto')) {
                if ($request->file('foto')->isValid()) {
                    $extencao = $request->file('foto')->extension();
                    if ($extencao != 'jpg') {
                        $array['errorBoolean'] = true;
                        $array['error'] = [ 'foto' => 'Arquivo com extenção diferente de JPG'];
                        return $array;
                    } else {
                        $foto = $request->file('foto')->store('public');
                    }
                } else {
                    $array['errorBoolean'] = true;
                    $array['error'] = [ 'foto' => 'Arquivo invalido'];
                    return $array;
                }
            }
            
            
            $nome = $request->input('nome');
            $email =  $request->input('email');
            $senha = Hash::make($request->input('senha'));
            $telefone =  $request->input('telefone');
            

            
            $registro->nome = $nome;
            $registro->email = $email;
            $registro->senha = $senha;
            $registro->telefone = $telefone;
            if ($request->input('url')) {
                $registro->foto= $request->input('url');
            } else {
                $url =  asset(Storage::url($foto));
                $registro->foto= $url;
            }
            
            $registro->save();
            $array['mensagem']= 'Registro aletrado com sucesso!!!';
            return $array;


            return $array;
        } else {
            $array['error'] = 'registro inexistente';
            return $array;
        }
    }

    

    public function verificaEmail($email)
    {
        $verifica = Registros::all()->where('email', '=', $email);
        
        
        if ($verifica) {
            $array['registro'] = $verifica;
            if (!count($array['registro']) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
}
