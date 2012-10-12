<?php
namespace Millwright\RadBundle\Paginator;

use Knp\Component\Pager\Paginator as BasePaginator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Lexik\Bundle\FormFilterBundle\Filter\QueryBuilderUpdaterInterface;
use Symfony\Component\Form\Form;

class KnpPaginator implements PaginatorInterface
{
    protected $paginator;

    /**
     * @var Request
     */
    protected $request;

    protected $queryUpdater;

    /**
     * Constructor
     *
     * @param BasePaginator $paginator
     */
    public function __construct(BasePaginator $paginator, QueryBuilderUpdaterInterface $queryUpdater)
    {
        $this->paginator    = $paginator;
        $this->queryUpdater = $queryUpdater;
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
            $this->queryUpdater->addFilterConditions($form, $target, $alias);
        }

        return $this->paginator->paginate($target, $page, $limit, $options);
    }
}
