<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->resolveTenant($request);

        if ($tenant === null) {
            abort(404);
        }

        if (! $tenant->active) {
            abort(403, 'Tenant inactivo.');
        }

        App::instance(Tenant::class, $tenant);

        return $next($request);
    }

    private function resolveTenant(Request $request): ?Tenant
    {
        // En desarrollo usamos el tenant del usuario autenticado.
        // En producción se puede extender para resolver por subdominio.
        $user = $request->user();

        if ($user === null) {
            return null;
        }

        $tenantId = $user->tenantUsers()->value('tenant_id');

        if ($tenantId === null) {
            return null;
        }

        return Tenant::find($tenantId);
    }
}
