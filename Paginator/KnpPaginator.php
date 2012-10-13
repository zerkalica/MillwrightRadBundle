<?php
namespace Millwright\RadBundle\Paginator;

use Knp\Component\Pager\Paginator as BasePaginator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Component\Form\Form;

class KnpPaginator implements PaginatorInterface
{
    protected $paginator;

    /**
     * @var Request
     */
    protected $request;

    protected $filterUpdater;

    /**
     * Constructor
     *
     * @param BasePaginator                 $paginator
     * @param FilterBuilderUpdaterInterface $filterUpdater
     */
    public function __construct(BasePaginator $paginator, FilterBuilderUpdaterInterface $filterUpdater)
    {
        $this->paginator     = $paginator;
        $this->filterUpdater = $filterUpdater;
    }

    /**
     * Kernel request event
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $this->request = $event->getRequest();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function paginate($target, $alias, Form $form = null)
    {
        $options['pageParameterName']          = $alias . '_page';
        $options['sortDirectionParameterName'] = $alias . '_direction';
        $options['sortFieldParameterName']     = $alias . '_sort';

        $page  = $this->request->get($options['pageParameterName'], 1);
        $limit = 5;

        if (null !== $form) {
            $this->filterUpdater->addFilterConditions($form, $target, $alias);
        }

        return $this->paginator->paginate($target, $page, $limit, $options);
    }
}
