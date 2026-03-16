<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = [], string $message = 'Request completed successfully.', array $meta = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => (object) $meta,
        ]);
    }

    public static function paginated(LengthAwarePaginator $paginator, mixed $data, string $message = 'Request completed successfully.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public static function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = ['message' => $message];

        if ($errors !== []) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
