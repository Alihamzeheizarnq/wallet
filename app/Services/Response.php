<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response
{
    private mixed $data = [];
    private array $errors = [];
    private string $messages = '';

    /**
     * message
     *
     * @param mixed $data
     * @return Response
     */
    public function data(mixed $data): Response
    {
        $this->data = $data;

        return $this;
    }

    /**
     * errors
     *
     * @param array|null $errors
     * @return Response
     */
    public function errors(?array $errors = []): Response
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * message
     *
     * @param string $message
     * @return Response
     */
    public function message(string $message): Response
    {
        $this->messages = $message;

        return $this;
    }

    /**
     * send
     *
     * @param int $statusCode
     * @return JsonResponse
     */
    public function send(int $statusCode = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'message' => $this->messages,
            'errors' => $this->errors,
            'data' => $this->data,
        ], $statusCode);
    }

}
