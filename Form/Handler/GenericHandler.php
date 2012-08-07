<?php
namespace Millwright\RadBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormView;

/**
 * Generic form hanlder
 */
abstract class GenericHandler implements GenericHandlerInterface
{
    protected $request;
    protected $requestMethods;
    protected $form;

    /**
     * Constructor
     *
     * @param Form    $form           form
     * @param Request $request        request
     * @param array   $requestMethods default
     */
    public function __construct(Form $form, Request $request, array $requestMethods = array())
    {
        $this->form           = $form;
        $this->request        = $request;
        $this->requestMethods = $requestMethods ? $requestMethods : array('POST');
    }

    /**
     * Handle form
     *
     * @return bool
     */
    protected function handle()
    {
        if (in_array($this->request->getMethod(), $this->requestMethods)) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                return true;
            }
        }

        return false;
    }
}
