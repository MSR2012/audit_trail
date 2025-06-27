<?php namespace App\Http\Middleware;

use App\Constants\Role;
use App\Repositories\IpRepository;
use Closure;
use Illuminate\Http\Request;

class CanEditIpAddress
{
    public function __construct(
        private IpRepository $ipRepository,
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (
            $request->headers->get('at-role') != Role::ADMIN &&
            !$this->ipRepository->get($request->route('id'))
        ) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
