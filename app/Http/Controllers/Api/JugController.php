<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChallengeRequest;
use Illuminate\Http\Request;

class JugController extends Controller
{
    public function waterJugChallenge(Request $request)
    {
        $bucketX = $request->input('bucket_x');
        $bucketY = $request->input('bucket_y');
        $measureZ = $request->input('measure_z');

        // Algoritmo DFS para resolver el Water Jug Challenge
        $solution = $this->solveWaterJugChallenge($bucketX, $bucketY, $measureZ);

        if ($solution) {
            return response()->json(['solution' => $solution]);
        } else {
            return response()->json(['message' => 'No Solution'], 404);
        }
    }

    /**
     * Método para resolver el Water Jug Challenge utilizando DFS.
     *
     * @param int $bucketX Capacidad del cubo X.
     * @param int $bucketY Capacidad del cubo Y.
     * @param int $measureZ Medida objetivo Z.
     * @param array $visited Estados visitados para evitar bucles infinitos.
     * @return array|bool Solución o false si no hay solución.
     */
    private function solveWaterJugChallenge($bucketX, $bucketY, $measureZ, $visited = [])
    {
        // Estado actual
        $state = [$bucketX, $bucketY];

        // Verificar si hemos llegado a la medida objetivo
        if ($state[0] == $measureZ || $state[1] == $measureZ || $state[0] + $state[1] == $measureZ) {
            return [$state];
        }

        // Marcar este estado como visitado
        $visited[] = $state;

        // Definir las acciones permitidas: FILL, EMPTY, TRANSFER
        $actions = ['FILL_X', 'FILL_Y', 'EMPTY_X', 'EMPTY_Y', 'TRANSFER_X_TO_Y', 'TRANSFER_Y_TO_X'];

        foreach ($actions as $action) {
            // Aplicar la acción y obtener el próximo estado
            $nextState = $this->applyAction($state, $action, $bucketX, $bucketY);

            // Verificar si el próximo estado es válido y no ha sido visitado
            if ($nextState && !in_array($nextState, $visited, true)) {
                // Recursivamente intentar encontrar una solución desde el próximo estado
                $solution = $this->solveWaterJugChallenge($nextState[0], $nextState[1], $measureZ, $visited);

                // Si se encuentra una solución, agregar el estado actual y devolver la solución
                if ($solution !== false) {
                    array_unshift($solution, $state);
                    return $solution;
                }
            }
        }

        // No se encontró ninguna solución desde este estado
        return false;
    }

    /**
     * Método para aplicar una acción al estado actual de los cubos.
     *
     * @param array $state Estado actual de los cubos.
     * @param string $action Acción a aplicar (FILL_X, FILL_Y, EMPTY_X, EMPTY_Y, TRANSFER_X_TO_Y, TRANSFER_Y_TO_X).
     * @param int $bucketX Capacidad del cubo X.
     * @param int $bucketY Capacidad del cubo Y.
     * @return array|bool Nuevo estado después de aplicar la acción o false si la acción no es válida.
     */
    private function applyAction($state, $action, $bucketX, $bucketY)
    {
        switch ($action) {
            case 'FILL_X':
                return [$bucketX, $state[1]];
            case 'FILL_Y':
                return [$state[0], $bucketY];
            case 'EMPTY_X':
                return [0, $state[1]];
            case 'EMPTY_Y':
                return [$state[0], 0];
            case 'TRANSFER_X_TO_Y':
                $remainingY = $bucketY - $state[1];
                $amountToTransfer = min($state[0], $remainingY);
                return [$state[0] - $amountToTransfer, $state[1] + $amountToTransfer];
            case 'TRANSFER_Y_TO_X':
                $remainingX = $bucketX - $state[0];
                $amountToTransfer = min($state[1], $remainingX);
                return [$state[0] + $amountToTransfer, $state[1] - $amountToTransfer];
            default:
                return false;
        }
    }
}
