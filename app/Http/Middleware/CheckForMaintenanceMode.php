<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class CheckForMaintenanceMode extends \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->app->isDownForMaintenance() &&(
                (!in_array($request->getClientIP(), config('setting.while_ip'))) ))
        {
            $data = json_decode(file_get_contents($this->app->storagePath().'/framework/down'), true);

            throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }
}
