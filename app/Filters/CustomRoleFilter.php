<?php

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Myth\Auth\Exceptions\PermissionException;
use Myth\Auth\Filters\BaseFilter;

class CustomRoleFilter extends BaseFilter implements FilterInterface
{
    /**
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // If no user is logged in then send them to the login form.
        if (! $this->authenticate->check()) {
            session()->set('redirect_url', current_url());

            return redirect($this->reservedRoutes['login']);
        }

        if (empty($arguments)) {
            return;
        }

        // Check each requested permission
        // foreach ($arguments as $group) {
        //     if ($this->authorize->inGroup($group, $this->authenticate->id())) {
        //         return;
        //     }
        // }

        $user = Services::authentication()->user();
        $requiredRoles = $arguments ?: [];
        $userRoles = $user->getRoles();

        // Check if the user has any of the required roles
        foreach ($requiredRoles as $role) {
            if (in_array($role, $userRoles)) {
                return; // User has at least one of the roles, allow access
            }
        }

        if ($this->authenticate->silent()) {
            $redirectURL = session('redirect_url') ?? route_to($this->landingRoute);
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL)->with('error', lang('Auth.notEnoughPrivilege'));
        }

        //throw new PermissionException(lang('Auth.notEnoughPrivilege'));
        return redirect()->to(site_url('unauthorized'));
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
