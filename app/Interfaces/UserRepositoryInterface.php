<?php

namespace App\Interfaces;

use App\Models\CommonConnection;
use App\Models\Connection;
use App\Models\User;

interface UserRepositoryInterface
{
    public function getSuggestions(User $user);

    public function sendConnectionRequest(User $user, User $targetUser);

    public function withdrawConnectionRequest(User $user, User $targetUser);

    public function getSentRequests(User $user);

    public function acceptConnectionRequest(Connection $connectionRequest);

    public function getReceivedRequests(User $user);

    public function getConnections(User $user);

    public function removeConnection(User $user, User $connectionUser);

    public function getCommonConnections(User $user, User $otherUser);
}
