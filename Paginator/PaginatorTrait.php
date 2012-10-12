<?php
namespace Millwright\RadBundle\Paginator;

use Millwright\RadBundle\Paginator\PaginatorInterface;
use Symfony\Component\Form\Form;

trait PaginatorTrait
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var string
     */
    //protected $alias;

    /**
     * Create paginator
     *
     * @param Form $form
     *
     * @return mixed
     */
    public function createPaginator(Form $form = null)
    {
        return $this->paginator->paginate($this->getSelectBuilder(), $this->alias, $form);
    }

    /**
     * Get query builder for select
     *
     * @return QueryBuilder|object
     */
    protected abstract function getSelectBuilder();
}
