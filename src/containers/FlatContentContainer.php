<?php

namespace zikwall\cellconstructor\containers;


class FlatContentContainer extends ContentContainer
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