<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

/**
 * Api Chain Authentication Filter.
 *
 * Checks all authentication systems specified within
 * `Config\Auth->authenticationChain` and returns 401 if failed.
 */
class ApiChainAuth implements FilterInterface
{
    use ResponseTrait;

    /**
     * Checks authenticators in sequence to see if the user is logged in through
     * either of authenticators.
     *
     * @param array|null $arguments
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        helper(['auth', 'setting']);

        $chain = config('Auth')->authenticationChain;

        foreach ($chain as $alias) {
            $auth = auth($alias);

            if ($auth->loggedIn()) {
                // Make sure Auth uses this Authenticator
                auth()->setAuthenticator($alias);

                $authenticator = $auth->getAuthenticator();

                if (setting('Auth.recordActiveDate')) {
                    $authenticator->recordActiveDate();
                }

                return;
            }
        }

        // Return 401 JSON
        $response = service('response');
        $response->setStatusCode(401);
        $response->setJSON(['status' => 401, 'error' => 'Unauthorized']);
        return $response;
    }

    /**
     * We don't have anything to do here.
     *
     * @param array|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
        // Nothing required
    }
}
