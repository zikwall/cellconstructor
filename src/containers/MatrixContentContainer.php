<?php

namespace zikwall\cellconstructor\containers;


class MatrixContentContainer extends ContentContainer
{
    /**
     * @param $tableName
     * @return null|\stdClass
     */
    public function findContainer($tableName)
    {
       return $this->container->table($tableName)->select($tableName.'.*')->get();
    }
}