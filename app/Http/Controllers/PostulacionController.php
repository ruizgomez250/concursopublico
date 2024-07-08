<?php

namespace App\Http\Controllers;

use App\Models\DetallesProceso;
use App\Models\Link;
use App\Models\PostulacionCabecera;
use App\Models\PostulacionDetalle;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostulacionController extends Controller
{

  
    public function __construct()
    {
        $this->middleware('permission:Listar Postulación',['only'=>['index','show']]);
        $this->middleware('permission:Guardar Postulación',['only'=>['store','create']]);
       // $this->middleware('permission:Actualizar Roles',['only'=>['update','edit']]);
        $this->middleware('permission:Eliminar Postulación',['only'=>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabecera = PostulacionDetalle::join('links', 'links.id', '=', 'postulacion_detalle.perfil')
            ->select('postulacion_detalle.codigo', 'postulacion_detalle.dependencia', 'postulacion_detalle.puesto', 'postulacion_detalle.tipo_concurso', 'postulacion_detalle.vacancia', 'links.documento', 'postulacion_detalle.informacion', 'postulacion_detalle.inicio', 'postulacion_detalle.fin', 'postulacion_detalle.estado', 'postulacion_detalle.id_cabecera')
            ->where('postulacion_detalle.estado', 1)
            ->get();
        $heads = [
            'Perfil', 'Dependencia', 'Profesión', 'Tipo de Concurso', 'Vacancia', 'Bases y Condiciones', 'Inicio de la Postulacion', 'Fin de la Postulacion', 'Accion'
        ];
        return view('postulacion.index', compact('cabecera', 'heads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $links = Link::all();
        $detalles = DetallesProceso::all();
        return view('postulacion.create', ['detalles' => $detalles, 'links' => $links]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'codigo.*' => 'required|string',
            'dependencia.*' => 'required|string',
            'puesto.*' => 'required|string',
            'tipo.*' => 'required|string',
            'vacancia.*' => 'required|integer',
            'link.*' => 'required|integer',
            'detalle.*' => 'required|integer',
            'inicio.*' => 'required|date',
            'fin.*' => 'required|date',
        ]);
        try {
            DB::beginTransaction();
            $postulacioncab = new PostulacionCabecera();

            // Asigna los datos recibidos del formulario al modelo
            $postulacioncab->descripcion = $request->input('descripcion');

            // Guarda el modelo en la base de datos
            $postulacioncab->save();
            foreach ($request->input('codigo') as $key => $codigo) {
                $postulaciondet = new PostulacionDetalle();
                $postulaciondet->id_cabecera = $postulacioncab->id;
                $postulaciondet->codigo = $codigo;
                $postulaciondet->dependencia = $request->input('dependencia')[$key];
                $postulaciondet->puesto = $request->input('puesto')[$key];
                $postulaciondet->tipo_concurso = $request->input('tipo')[$key];
                $postulaciondet->vacancia = $request->input('vacancia')[$key];
                $postulaciondet->perfil = $request->input('link')[$key];
                $postulaciondet->informacion = $request->input('detalle')[$key];
                $postulaciondet->inicio = $request->input('inicio')[$key];
                $postulaciondet->fin = $request->input('fin')[$key];
                $postulaciondet->estado = 1; // Asigna un valor por defecto para el estado, ajusta según sea necesario
                $postulaciondet->save();
            }
            DB::commit();
            return redirect()->route('postulacion.create')->with([
                'success' => 'La postulacion se ha registrado correctamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('postulacion.create')->with('error', 'Ha ocurrido un error al registrar la Postulacion. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Encontrar la cabecera por su id
                $cabecera = PostulacionCabecera::findOrFail($id);

                // Eliminar todos los detalles asociados
                $cabecera->detalles()->delete();

                // Eliminar la cabecera
                $cabecera->delete();
            });

            // Preparar datos para la vista si la eliminación fue exitosa
            $cabeceras = PostulacionCabecera::all();  // Asumiendo que quieres listar todas las cabeceras restantes
            return redirect()->route('postulacion.index')->with([
                'success' => 'La postulacion se ha borrado correctamente.',
            ]);

        } catch (Exception $e) {
            // Manejar cualquier excepción que ocurra durante la transacción
            return redirect()->route('postulacion.index')->with([
                'error' => 'Ha ocurrido un error al intentar borrar.',
            ]);
        }
    }
}
