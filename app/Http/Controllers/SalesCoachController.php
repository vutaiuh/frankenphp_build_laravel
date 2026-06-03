<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Documentation',
    description: 'API Description'
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'API Server'
)]
class SalesCoachController extends Controller
{
    #[OA\Post(
        path: '/api/sales-coach/analyze',
        summary: 'Analyze sales transcript',
        tags: ['Sales Coach'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['transcript'],
                properties: [
                    new OA\Property(property: 'transcript', type: 'string', example: 'Hello, I would like to buy...')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Analysis result',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function analyze(Request $request)
    {
        $request->validate([
            'transcript' => ['required', 'string'],
        ]);

        $response = SalesCoach::make()->prompt(
            $request->transcript
        );

        return response()->json([
            'result' => (string) $response,
        ]);
    }
}
