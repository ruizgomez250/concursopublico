<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LinkController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:Documento Listar',['only'=>['index','show']]);
        $this->middleware('permission:Documento Guardar',['only'=>['store','create']]);
       $this->middleware('permission:Documento Actualizar',['only'=>['update','edit']]);
        $this->middleware('permission:Documento Eliminar',['only'=>['destroy']]);
    }

    public function index(): View
    {

        try {
            $links = Link::all();
            $heads = [
                'ID', 'Descripcion', 'Documento'
            ];
            return view('link.index', ['links' => $links, 'heads' => $heads]);
        } catch (Exception $e) {
            return view('clientes.index')->with('message', 'No se pudo completar la operación.');
        }
    }
    public function create(): View
    {

        try {

            return view('link.create');
        } catch (Exception $e) {
            return view('link.index')->with('message', 'No se pudo completar la operación.');
        }
    }
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'descripcion' => 'required|string',
                'documento' => 'required|mimes:pdf,doc,docx|max:2048',
            ]);

            $file = $request->file('documento');

            $descripcion = substr($request->input('descripcion'), 0, 15);
            $descripcionSinEspacios = str_replace(' ', '_', $descripcion);
            $fechaHora = date('Ymd_His');
            $extension = $file->getClientOriginalExtension();
            $nombreNuevo = $descripcionSinEspacios . '_' . $fechaHora . '.' . $extension;

            // Iniciar transacción
            DB::beginTransaction();

            $link = new Link();
            $link->descripcion = $request->input('descripcion');
            $link->documento = $nombreNuevo; // Se almacena la ruta completa
            $link->save();

            // Mover el archivo a la ubicación de destino
            if ($file->move(public_path('documentos'), $nombreNuevo)) {
                // Confirmar la transacción si la copia es exitosa
                DB::commit();
                return redirect()->route('link.create', $link->id)->with('success', true)->with('custom_message', 'Documento Actualizado!')->with('tab', 'academico');
            } else {
                // Revertir la transacción si la copia falla
                dd('POS NO');
                DB::rollBack();
                return redirect()->route('link.create', $link->id)->with('error', 'No se pudo copiar el archivo.');
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de cualquier otro error

            DB::rollBack();
            dd($e);
            return redirect()->route('link.create')->with('error', 'No se pudo completar la operación.');
        }
    }
}
