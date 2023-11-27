<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JugController extends Controller
{
    public function waterJugChallenge(Request $request)
    {
        $bucketX = $request->input('bucket_x');
        $bucketY = $request->input('bucket_y');
        $measureZ = $request->input('measure_z');

        $result = $this->bfs($bucketX, $bucketY, $measureZ);

        return response()->json(['solution' => $result]);
    }

    private function bfs($bucketX, $bucketY, $measureZ)
    {
        $queue = new \SplQueue();
        $visited = [];

        // Initial state (both jugs empty)
        $initialState = [0, 0];
        $queue->enqueue([$initialState, []]);

        while (!$queue->isEmpty()) {
            list($currentState, $steps) = $queue->dequeue();

            if ($currentState[0] == $measureZ || $currentState[1] == $measureZ) {
                return $this->formatSolution($steps); // Cambio aquí
            }

            $nextStates = $this->generateNextStates($currentState, $bucketX, $bucketY);

            foreach ($nextStates as $nextState) {
                if (!isset($visited[$nextState[0]][$nextState[1]])) {
                    $visited[$nextState[0]][$nextState[1]] = true;
                    $queue->enqueue([$nextState, array_merge($steps, [$nextState])]);
                }
            }
        }

        // No solution found
        return 'No Solution';
    }

    private function generateNextStates($currentState, $bucketX, $bucketY)
    {
        $nextStates = [];

        // Define actions for better clarity
        $actions = [
            'Fill bucket X', 'Fill bucket Y', 'Empty bucket X', 'Empty bucket Y', 'Transfer bucket X to bucket Y', 'Transfer bucket Y to bucket X'
        ];

        // FILL Bucket X
        $nextStates[] = [$bucketX, $currentState[1], $actions[0]];

        // FILL Bucket Y
        $nextStates[] = [$currentState[0], $bucketY, $actions[1]];

        // EMPTY Bucket X
        $nextStates[] = [0, $currentState[1], $actions[2]];

        // EMPTY Bucket Y
        $nextStates[] = [$currentState[0], 0, $actions[3]];

        // TRANSFER Bucket X to Bucket Y
        $transferXY = min($currentState[0], $bucketY - $currentState[1]);
        $nextStates[] = [$currentState[0] - $transferXY, $currentState[1] + $transferXY, $actions[4]];

        // TRANSFER Bucket Y to Bucket X
        $transferYX = min($bucketX - $currentState[0], $currentState[1]);
        $nextStates[] = [$currentState[0] + $transferYX, $currentState[1] - $transferYX, $actions[5]];

        return $nextStates;
    }

    private function formatSolution($steps)
    {
        $formattedSteps = [];

        foreach ($steps as $step) {
            $formattedStep = [
                'Action' => end($step),
                'X' => $step[0],
                'Y' => $step[1],
            ];

            $formattedSteps[] = $formattedStep;
        }

        return $formattedSteps;
    }
}
