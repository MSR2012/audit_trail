<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateRecordException;
use App\Services\Ips\IpServiceInterface;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class IpController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly IpServiceInterface $ipService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->ipService->list());
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'ip_address' => 'required|ip',
                'label' => 'required|string',
                'comment' => 'string',
            ]);

            $responseBody = $this->ipService->store(
                $request->header('at-user-id'),
                $request->input('ip_address'),
                $request->input('label'),
                (string)$request->input('comment'),
            );

            return response()->json(
                array_merge(
                    $responseBody,
                    [
                        'message' => 'Ip added successfully.',
                    ]
                ), ResponseAlias::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'error_message' => $this->getMessageFromErrors($e->errors()),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (DuplicateRecordException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->ipService->get($id));
        } catch (RecordsNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->validate($request, [
                'label' => 'required|string',
                'comment' => 'string',
            ]);

            return response()->json(
                array_merge(
                    $this->ipService->update(
                        $id,
                        $request->input('label'),
                        $request->input('comment', '')
                    ),
                    [
                        'message' => 'Ip updated successfully.',
                    ]
                ), ResponseAlias::HTTP_OK
            );
        } catch (ValidationException $e) {
            return response()->json([
                'error_message' => $this->getMessageFromErrors($e->errors()),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (RecordsNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $this->ipService->delete($id);

            return response()->json(['message' => "Ip deleted successfully."], ResponseAlias::HTTP_OK);
        } catch (RecordsNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
