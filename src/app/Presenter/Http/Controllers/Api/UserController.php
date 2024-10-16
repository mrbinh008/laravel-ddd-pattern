<?php

namespace App\Presenter\Http\Controllers\Api;

use App\Application\Handlers\CreateUserHandler;
use App\Application\Handlers\GetListUserHandler;
use App\Application\Handlers\UpdateUserHandler;
use App\Domain\Shared\Queries\BaseGetListQuery;
use App\Domain\User\CreateUserData;
use App\Domain\User\UpdateUserData;
use App\Domain\User\UserData;
use App\Infrastructure\Database\Models\User;
use App\Presenter\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix('users')]
class UserController extends Controller
{
    public function __construct(
        private readonly GetListUserHandler $getListUserHandler,
        private readonly CreateUserHandler  $createUserHandler,
        private readonly UpdateUserHandler  $updateUserHandler
    )
    {
    }

    /**
     * Get list user
     * @param Request $request
     * @return JsonResponse
     */
    #[Get('/', name: 'users.index')]
    public function index(Request $request): JsonResponse
    {
        $filter = [];
        $sort = $request->input('sort', 'asc');

        $getUserListQuery = new BaseGetListQuery($filter, [], $sort);
        $users = Bus::dispatchNow($getUserListQuery, $this->getListUserHandler);

        return $this->responsePaginate(UserData::collect($users, PaginatedDataCollection::class));
    }

    /**
     * Create user
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('POST', '/', 'users.store')]
    public function store(Request $request): JsonResponse
    {
        $createUserData = CreateUserData::from([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        $result = Bus::dispatchNow($createUserData, $this->createUserHandler);

        return $this->responseOk(UserData::from($result));
    }

    #[Route('GET', '/{user}', 'show')]
    public function show(User $user): JsonResponse
    {
        return $this->responseOk(UserData::from($user));
    }

    #[Route('PUT', '/{user}', 'users.update')]
    public function update(User $user, Request $request): JsonResponse
    {
        $updateUserData = UpdateUserData::from([
            'user' => $user,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        $result = Bus::dispatchNow($updateUserData, $this->updateUserHandler);

        return $this->responseOk($result);
    }
}
