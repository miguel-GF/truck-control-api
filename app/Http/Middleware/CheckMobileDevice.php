<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckMobileDevice
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$userAgent = $request->header('User-Agent');

		Log::info($userAgent);
		if ($this->isMobileDevice($userAgent)) {
			return $next($request);
		}

		abort(403, 'Acceso no autorizado.');
	}

	private function isMobileDevice($userAgent)
	{
		// return (bool) preg_match('/(Android|iPhone|iPod|iPad)/i', $userAgent);
		return (bool) preg_match('/(Android)/i', $userAgent);
	}
}
