<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Libro;

class Libros extends Controller{
    public function index(){

        $libro = new Libro();
        $datos['libros'] = $libro->orderBy('id','ASC')->findAll();

        $datos['header'] = view('template/header');
        $datos['footer'] = view('template/footer');

        return view('libros/listar',$datos);
    }

    public function crear(){
        $datos['header'] = view('template/header');
        $datos['footer'] = view('template/footer');
        return view('libros/crear',$datos);
    }

    public function guardar(){

        $libro = new Libro();

        $validation = $this->validate([
            'nombre' => 'required|min_length[3]',
            'imagen' =>[
                'uploaded[imagen]',
                'mime_in[imagen,image/jpg,image/jpeg,image/png]',
                'max_size[imagen,1024]',
            ]
        ]);
        if (!$validation){
            $session = session();
            $session->setFlashdata('mensaje', 'Revise la información');
            return redirect()->back()->withInput();
        }

        if ($imagen = $this->request->getFile('imagen')){
          $nuevoNombre = $imagen->getRandomName();
          $imagen->move('../public/uploads/',$nuevoNombre);
          $datos = [
              'nombre'=>$this->request->getVar('nombre'),
              'imagen'=>$nuevoNombre
          ];
          $libro->insert($datos);
      }
      return $this->response->redirect(site_url('/listar'));
    }

    public function borrar($id=null) {

        $libro = new Libro();
        $datosLibro = $libro->where('id',$id)->first();
        $ruta = ('../public/uploads/'.$datosLibro['imagen']);
        unlink($ruta);
        $libro->where('id',$id)->delete();

        return $this->response->redirect(site_url('/listar'));
    }

    public function editar($id=null){
        $libro = new Libro();
        $datos['libro'] = $libro->where('id',$id)->first();

        $datos['header'] = view('template/header');
        $datos['footer'] = view('template/footer');

        return view('libros/editar',$datos);
    }

    public function actualizar(){
        $libro = new Libro();
        $datos = [
            'nombre' => $this->request->getVar('nombre')
        ];
        $id = $this->request->getVar('id');

        $validation = $this->validate([
            'nombre' => 'required|min_length[3]',
        ]);
        if (!$validation){
            $session = session();
            $session->setFlashdata('mensaje', 'Revise la información');
            return redirect()->back()->withInput();
        }

        $libro->update($id,$datos);
        $validation = $this->validate([
            'imagen' =>[
                'uploaded[imagen]',
                'mime_in[imagen,image/jpg,image/jpeg,image/png',
                'max_size[imagen,1024]',
            ]
        ]);

        if ($validation){
            if ($imagen = $this->request->getFile('imagen')){
                $datosLibro = $libro->where('id',$id)->first();
                $ruta = ('../public/uploads/'.$datosLibro['imagen']);
                unlink($ruta);

                $nuevoNombre = $imagen->getRandomName();
                $imagen->move('../public/uploads/',$nuevoNombre);
                $datos = [
                    'imagen'=>$nuevoNombre
                ];
                $libro->update($id,$datos);
            }
        }
        return $this->response->redirect(site_url('/listar'));
    }
}