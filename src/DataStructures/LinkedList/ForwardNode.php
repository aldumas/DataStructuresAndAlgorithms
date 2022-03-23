<?php

namespace DataStructures\LinkedList;

class ForwardNode {
    public $data = null;
    public $next = null;

    public function __construct($data, $next=null)
    {
        $this->data = $data;
        $this->next = $next;
    }
}
