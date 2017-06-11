<?php

namespace AppBundle\Services;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;

/**
 * Description of Paginator
 *
 * @author Daniel
 */
class Paginator implements PaginatorAwareInterface {

    protected $paginator;
    
    /**
     * Return Pagination
     * @param type $list
     * @param integer $numberPag
     * @param integer $numberElementsPerPage
     * @return Pagination
     */
    public function getPagination($list, $numberPag, $numberElementsPerPage) {

        return $pagination = $this->paginator->paginate(
                $list, $numberPag, $numberElementsPerPage
        );
    }


    public function setPaginator(\Knp\Component\Pager\Paginator $paginator) {
        $this->paginator = $paginator;
    }

}
