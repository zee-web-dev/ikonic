<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\CommonConnection;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRepository implements UserRepositoryInterface
{
    public function getSuggestions(User $user): Builder
    {
        return User::whereNot('id', $user->id)->whereNotIn('id', $user->commonConnections->pluck('id'))
            ->whereDoesntHave('sentConnectionRequests', function ($query) use ($user) {
                $query->where('receiver_id', $user->id);
            })
            ->whereDoesntHave('receivedConnectionRequests', function ($query) use ($user) {
                $query->where('sender_id', $user->id);
            });
    }

    public function sendConnectionRequest(User $user, User $targetUser): Void
    {
        $user->sentConnectionRequests()->create(['receiver_id' => $targetUser->id]);
    }

    public function withdrawConnectionRequest(User $user, User $targetUser): Void
    {
        $user->sentConnectionRequests()
            ->where('receiver_id', $targetUser->id)
            ->delete();
    }

    public function getSentRequests(User $user): Builder
    {
        return Connection::with('receiver')
            ->where('sender_id', $user->id)->where('status', 'pending');
    }

    public function acceptConnectionRequest(Connection $connectionRequest): Void
    {
        $connectionRequest->update(['status' => 'accepted']);

        // Create a new connection
        CommonConnection::create([
            'user_id' => $connectionRequest->receiver_id,
            'connected_user_id' => $connectionRequest->sender_id,
        ]);
    }

    public function getReceivedRequests(User $user): Builder
    {
        return Connection::with('sender')
            ->where('receiver_id', $user->id)->where('status', 'pending');
    }

    public function getConnections(User $user): BelongsToMany
    {
        return $user->commonConnections();
    }

    public function removeConnection(User $user, User $connectionUser): Void
    {
        $user->commonConnections()->detach($connectionUser->id);
    }

    public function getCommonConnections(User $user, User $otherUser): BelongsToMany
    {
        return $user->commonConnections()
            ->whereIn('connected_user_id', $otherUser->commonConnections->pluck('id'));
    }
}
