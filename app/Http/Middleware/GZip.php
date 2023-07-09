<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GZip
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		if ($this->shouldCompress($response)) {
			$response->setContent(gzencode($response->getContent(), 9));
			$response->headers->set('Content-Encoding', 'gzip');
			$response->headers->set('Vary', 'Accept-Encoding');
		}

		return $response;
	}

	protected function shouldCompress($response)
	{
		// Verificar si la respuesta es una descarga de archivo
        if ($response->headers->has('Content-Disposition')) {
            $contentDisposition = $response->headers->get('Content-Disposition');
            if (str_contains($contentDisposition, 'attachment')) {
                return false; // No comprimir descargas de archivo
            }
        }
		return true;
	}
}
