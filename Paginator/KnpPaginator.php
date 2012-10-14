<?php
namespace Millwright\RadBundle\Paginator;

use Knp\Component\Pager\Paginator as BasePaginator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Form\Form;

use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;

use Millwright\ConfigurationBundle\Builder\OptionRegistryInterface;

class KnpPaginator implements PaginatorInterface
{
    protected $paginator;

    /**
     * @var Request
     */
    protected $request;

    protected $filterUpdater;

    protected $options;

    /**
     * Constructor
     *
     * @param BasePaginator                 $paginator
     * @param FilterBuilderUpdaterInterface $filterUpdater
     * @param OptionRegistryInterface       $options
     */
    public function __construct(
        BasePaginator $paginator,
        FilterBuilderUpdaterInterface $filterUpdater,
        OptionRegistryInterface $options
    ) {
        $this->paginator     = $paginator;
        $this->filterUpdater = $filterUpdater;
        $this->options       = $options;
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
        $options = array(
            'pageParameterName'          => 'page',
            'sortDirectionParameterName' => 'direction',
            'sortFieldParameterName'     => 'sort',
        );

        foreach ($options as $name => $value) {
            $paramName      = $alias . '_' . $value;
            $options[$name] = $paramName;
            if ($name !== 'pageParameterName') {
                $this->options->addOption($alias, $paramName, $this->request->get($paramName));
            }
        }

        $page  = $this->request->get($options['pageParameterName'], 1);
        $limit = 5;

        if (null !== $form) {
            $this->filterUpdater->addFilterConditions($form, $target, $alias);
        }

        return $this->paginator->paginate($target, $page, $limit, $options);
    }
}
