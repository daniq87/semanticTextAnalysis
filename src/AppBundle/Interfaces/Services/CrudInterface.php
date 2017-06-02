<?php

namespace AppBundle\Interfaces\Services;

/**
 * Interface with the basic methods: create, read, update and delete
 * @author Daniel
 */
interface CrudInterface {
    
    public function findById($id);
    
    public function create($object);
    
    public function update($object);

    public function delete($object);
}
