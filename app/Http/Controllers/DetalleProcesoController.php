<?php

namespace App\Http\Controllers;

use App\Models\DetallesProceso;
use App\Models\DetallesProcesoDetalle;
use App\Models\Link;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetalleProcesoController extends Controller
{
    public function index()
    {
        $cabecera = DetallesProceso::all();
        $heads = [
            'ID', 'Descripcion', 'Acción'
        ];
        return view('procesodetalle.index', compact('cabecera', 'heads'));
    }
    public function create()
    {
        $links = Link::all();
        return view('procesodetalle.create', ['links' => $links]);
    }
    public function edit($id): View
    {

        try {
            $links = Link::all();
            $detalleCab = DetallesProceso::find($id);
            $detallesProc = DetallesProcesoDetalle::where('iddetalle', $id)->get();
            return view('procesodetalle.edit', ['detalleCab' => $detalleCab, 'detallesProc' => $detallesProc, 'links' => $links]);
        } catch (Exception $e) {
            return view('procesodetalle.index')->with('message', 'No se pudo completar la operación.');
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'fecha.*' => 'required|date',
            'texto.*' => 'nullable|string',
            'link.*' => 'nullable|integer', // Aquí puedes ajustar la validación según tus necesidades
        ]);

        try {
            DB::beginTransaction();
            $detalleProceso = new DetallesProceso();

            // Asigna los datos recibidos del formulario al modelo
            $detalleProceso->descripcion = $request->input('descripcion');

            // Guarda el modelo en la base de datos
            $detalleProceso->save();
            foreach ($request->input('fecha') as $key => $fecha) {
                // Crea una nueva instancia del modelo DetallesProcesoDetalle
                $detalle = new DetallesProcesoDetalle();

                // Asigna los datos recibidos del formulario al modelo
                $detalle->fecha = $fecha;
                $detalle->texto = $request->input('texto')[$key];
                $detalle->link = $request->input('link')[$key];
                $detalle->iddetalle = $detalleProceso->id; // Asigna el ID del detalle principal del proceso

                // Guarda el modelo en la base de datos
                $detalle->save();
            }
            DB::commit();
            return redirect()->route('detalleproceso.create')->with([
                'success' => 'El detalle se ha registrado correctamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('detalleproceso.create')->with('error', 'Ha ocurrido un error al registrar el detalle. Por favor, inténtelo de nuevo.');
        }
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'descripcion' => 'required|string',
        'detalles.*.fecha' => 'required|date',
        'detalles.*.texto' => 'nullable|string',
        'detalles.*.link' => 'nullable|integer', // Aquí puedes ajustar la validación según tus necesidades
        'fecha.*' => 'required|date',
        'texto.*' => 'nullable|string',
        'link.*' => 'nullable|integer', // Aquí puedes ajustar la validación según tus necesidades
    ]);

    try {
        DB::beginTransaction();

        // Encuentra el detalle del proceso principal por su ID
        $detalleProceso = DetallesProceso::findOrFail($id);

        // Actualiza los datos recibidos del formulario en el modelo
        $detalleProceso->descripcion = $request->input('descripcion');
        $detalleProceso->save();

        // Obtener los IDs de los detalles actuales para verificar cuáles eliminar
        $detalleIds = array_filter(array_column($request->input('detalles'), 'id'));

        // Eliminar los detalles que no están en el formulario
        DetallesProcesoDetalle::where('iddetalle', $id)->whereNotIn('id', $detalleIds)->delete();

        foreach ($request->input('detalles') as $detalleData) {
            $detalleId = $detalleData['id'] ?? null;

            if ($detalleId) {
                // Actualizar detalles existentes
                $detalle = DetallesProcesoDetalle::findOrFail($detalleId);
            } else {
                // Crear nuevos detalles
                $detalle = new DetallesProcesoDetalle();
                $detalle->iddetalle = $detalleProceso->id;
            }

            $detalle->fecha = $detalleData['fecha'];
            $detalle->texto = $detalleData['texto'] ?? null;
            $detalle->link = $detalleData['link'] ?? null;
            $detalle->save();
        }

        // Manejar los nuevos detalles
        if ($request->has('fecha')) {
            foreach ($request->input('fecha') as $key => $fecha) {
                $detalle = new DetallesProcesoDetalle();
                $detalle->iddetalle = $detalleProceso->id;
                $detalle->fecha = $fecha;
                $detalle->texto = $request->input('texto')[$key] ?? null;
                $detalle->link = $request->input('link')[$key] ?? null;
                $detalle->save();
            }
        }

        DB::commit();
        return redirect()->route('detalleproceso.index')->with('success', 'El detalle se ha actualizado correctamente.');
    } catch (Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        return redirect()->route('detalleproceso.index')->with('error', 'Ha ocurrido un error al actualizar el detalle. Por favor, inténtelo de nuevo.');
    }
}
}
