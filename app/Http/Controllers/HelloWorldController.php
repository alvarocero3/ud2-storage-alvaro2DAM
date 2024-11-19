<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelloWorldController extends Controller
{
    /**
     * Lista todos los ficheros de la carpeta storage/app.
     *
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: Un array con los nombres de los ficheros.
     */
    public function index()
    {
        $ficheros = Storage::files();

        // Depuración: Verificar el contenido de $ficheros
        // Preparar la respuesta
        $respuesta = [
            'mensaje' => 'Listado de ficheros',
            'contenido' => $ficheros,
        ];

        // Devolver la respuesta en formato JSON
        return response()->json($respuesta);
    }

     /**
     * Recibe por parámetro el nombre de fichero y el contenido. Devuelve un JSON con el resultado de la operación.
     * Si el fichero ya existe, devuelve un 409.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function store(Request $request)
    {
        $filename = $request->input('filename');
        $content = $request->input('content');

        if (!$filename || !$content) {
            return response()->json([
                'mensaje' => 'Faltan parámetros: filename y content son obligatorios'
            ], 422);
        }

        // Verificar si el archivo ya existe usando Storage
        if (Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo ya existe'
            ], 409);
        }

    // Intentar guardar el archivo
    try {
        Storage::put($filename, $content);  // Guardar archivo usando Storage
        return response()->json([
            'mensaje' => 'Guardado con éxito'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'mensaje' => 'Hubo un error al guardar el fichero'
        ], 500);
    }
    }

     /**
     * Recibe por parámetro el nombre de fichero y devuelve un JSON con su contenido
     *
     * @param name Parámetro con el nombre del fichero.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: El contenido del fichero si se ha leído con éxito.
     */
    public function show(string $filename)
    {
        if(!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'Archivo no encontrado',
            ], 404);
        }
    

        $content = Storage::get($filename);

        return response()->json([
            'mensaje' => 'Archivo leído con éxito',
            'contenido' => $content,
        ]);
    }

    /**
     * Recibe por parámetro el nombre de fichero, el contenido y actualiza el fichero.
     * Devuelve un JSON con el resultado de la operación.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function update(Request $request, string $filename)
    {
        $content = $request->input('content');
    
        if (!$content) {
            return response()->json([
                'mensaje' => 'El parámetro content es obligatorio.'
            ], 422);
        }
    
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe'
            ], 404);
        }
    
        try {
            Storage::put($filename, $content);  // Actualizar archivo usando Storage
            return response()->json([
                'mensaje' => 'Actualizado con éxito'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un error al actualizar el fichero.'
            ], 500);
        }
    }

    /** 
     * Recibe por parámetro el nombre de ficher y lo elimina.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     */
     //El JSON devuelto debe tener las siguientes claves:
     //- mensaje: Un mensaje indicando el resultado de la operacion.
     
     public function destroy(string $filename)
     {
          // Comprobamos si el archivo existe
     if (!Storage::exists($filename)) {
         return response()->json([
             'mensaje' => 'El archivo no existe',
         ], 404);
     }
 
     // Eliminamos el archivo
     Storage::delete($filename);
 
     // Respondemos con un mensaje de éxito
     return response()->json([
         'mensaje' => 'Eliminado con éxito',
     ]);
     }
}