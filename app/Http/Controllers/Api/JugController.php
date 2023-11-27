<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Water Jug Challenge API",
 *     version="1.0.0",
 *     description="API for solving the Water Jug Challenge",
 *     @OA\Contact(
 *         email="your-email@example.com",
 *         name="Your Name"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */

class JugController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/water-jug-challenge",
     *     tags={"Water Jug Challenge"},
     *     summary="Solve the Water Jug Challenge",
     *     operationId="waterJugChallenge",
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for the Water Jug Challenge",
     *         @OA\JsonContent(
     *             required={"bucket_x", "bucket_y", "measure_z"},
     *             @OA\Property(property="bucket_x", type="integer", example=10),
     *             @OA\Property(property="bucket_y", type="integer", example=2),
     *             @OA\Property(property="measure_z", type="integer", example=4),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with the solution",
     *         @OA\JsonContent(
     *             @OA\Property(property="solution", type="array", @OA\Items(
     *                 @OA\Property(property="Action", type="string"),
     *                 @OA\Property(property="X", type="integer"),
     *                 @OA\Property(property="Y", type="integer"),
     *             )),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No solution found",
     *         @OA\JsonContent(
     *             @OA\Property(property="solution", type="string", example="No Solution"),
     *         ),
     *     ),
     * )
     */

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
