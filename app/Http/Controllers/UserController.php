<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\CommonConnection;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    // Get Suggestions
    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $suggestions = $this->userRepository->getSuggestions($user)->paginate($request->paginate ? $request->paginate : 10);

            return response()->json($suggestions);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Send Connection Request
    public function sendConnectionRequest(Request $request, User $targetUser): JsonResponse
    {
        try {
            $user = auth()->user();
            $this->userRepository->sendConnectionRequest($user, $targetUser);

            return response()->json(['message' => 'Connection request sent successfully.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Withdraw Connection Request
    public function withdrawConnectionRequest(Request $request, User $targetUser): JsonResponse
    {
        try {
            $user = auth()->user();
            $this->userRepository->withdrawConnectionRequest($user, $targetUser);

            return response()->json(['message' => 'Connection request withdrawn.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Get Sent Requests of a User
    public function getSentRequests(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $sentRequests = $this->userRepository->getSentRequests($user)->paginate(10);

            return response()->json($sentRequests);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Accept Connection Request
    public function acceptConnectionRequest(Request $request, Connection $connectionRequest): JsonResponse
    {
        try {
            $this->userRepository->acceptConnectionRequest($connectionRequest);

            return response()->json(['message' => 'Connection request accepted.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Get Received Requests
    public function getReceivedRequests(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $receivedRequests = $this->userRepository->getReceivedRequests($user)->paginate(10);

            return response()->json($receivedRequests);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Get All Connections of User
    public function getConnections(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $connections = $this->userRepository->getConnections($user)->paginate(10);

            return response()->json($connections);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Remove Connection
    public function removeConnection(Request $request, User $connectionUser): JsonResponse
    {
        try {
            $user = auth()->user();
            $this->userRepository->removeConnection($user, $connectionUser);

            return response()->json(['message' => 'Connection removed.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Get Common Connections
    public function getCommonConnections(Request $request, User $otherUser): JsonResponse
    {
        try {
            $user = auth()->user();
            $commonConnections = $this->userRepository->getCommonConnections($user, $otherUser)->paginate(10);

            return response()->json($commonConnections);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => '',
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Count Users like total suggestions etc.
    public function countUsers(): array
    {
        $user = auth()->user();
        $data = [];
        $data['total_suggestions'] = $this->userRepository->getSuggestions($user)->count();
        $data['total_sent_req'] =  $this->userRepository->getSentRequests($user)->count();
        $data['total_rec_req'] = $this->userRepository->getReceivedRequests($user)->count();
        $data['total_connections'] = $this->userRepository->getConnections($user)->count();
        return $data;
    }
}
