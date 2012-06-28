<?php
namespace Millwright\RadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Generic controller
 */
abstract class Controller extends BaseController
{
    const FLASH_ERROR   = 'error';
    const FLASH_WARNING = 'warning';
    const FLASH_INFO    = 'info';
    const FLASH_SUCCESS = 'success';

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return \Symfony\Component\Routing\Router
     */
    public function getRouter()
    {
        return $this->get('router');
    }

    /**
     * @return \Symfony\Component\Translation\Translator
     */
    public function getTranslator()
    {
        return $this->get('translator');
    }

    protected function getTranslationDomain()
    {
        //autodetect domain
        $parts = explode('\\', get_class($this));

        return $parts[0] . $parts[1];
    }

    /**
     * Translate message
     *
     * @param        $id
     * @param array  $parameters
     * @param string $domain
     * @param null   $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if (!$domain) {
            $domain = $this->getTranslationDomain();
        }

        return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Set flash message to session
     *
     * @param string|array $value array('message': string, 'params': array, 'domain': string)
     * @param string $name flash message type: error, warning, info, success @see self::FLASH_* constants
     *
     * @return void
     */
    public function flash($value, $name = self::FLASH_SUCCESS)
    {
        if (is_array($value)) {
            $value = $this->trans($value['message'], $value['params'], $value['domain']);
        }
        $this->getSession()->getFlashBag()->set($name, $value);
    }

    /**
     * Redirect and show flash message
     *
     * @param string                $route         route name
     * @param array|null            $parameters    route parameters
     * @param string|null           $message       flash message
     * @param array|null            $messageParams flash message parameters
     * @param string                $domain        flash message domain
     * @param int                   $status        http redirect status code
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectWithMessage(
        $route,
        $parameters = null,
        $message = null,
        $messageParams = null,
        $domain = null,
        $status = 302)
    {
        if ($message) {
            $this->flash(array('message' => $message, 'params' => $messageParams, 'domain' => $domain));
        }

        return $this->redirectRoute($route, $parameters, $status);
    }

    /**
     * Redirecto to route
     *
     * @param string     $route
     * @param array|null $parameters
     * @param int        $status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectRoute($route, $parameters = null, $status = 302)
    {
        return $this->redirect($this->generateUrl($route, (array) $parameters, $status));
    }

    /**
     * Assert if entity found and throw exception if not found
     *
     * @param object      $entity
     * @param int         $id
     * @param string      $message
     * @param string|null $domain null for autodetect by classname
     *
     * @throws NotFoundHttpException
     *
     * @return void
     */
    protected function assertFound($entity, $id, $message, $domain = null)
    {
        if (null === $entity) {
            throw new NotFoundHttpException($this->trans($message, array('%id%' => $id), $domain));
        }
    }
}
