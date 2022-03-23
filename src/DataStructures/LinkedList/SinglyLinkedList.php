<?php

namespace DataStructures\LinkedList;

class SinglyLinkedList
{
    private $head = null;

    public function is_empty()
    {
        return is_null($this->head);
    }

    public function append($item)
    {
        $node = new ForwardNode($item);

        if (is_null($this->head))
            $this->head = $node;
        else
            $this->last_node()->next = $node;
    }

    private function last_node()
    {
        if (is_null($this->head))
            return null;

        $last = $this->head;
        $next = $last->next;

        while (!is_null($next)) {
            $last = $next;
            $next = $last->next;
        }

        return $last;
    }

    public function prepend($item)
    {
        $this->head = new ForwardNode($item, $this->head);
    }

    public function items()
    {
        for ($node = $this->head; !is_null($node); $node = $node->next) {
            yield $node->data; // PHP also makes a sequential key available to caller
        }
    }

    public function first()
    {
        if (is_null($this->head))
            throw new InvalidOperationException('no first item');

        return $this->head->data;
    }

    public function last()
    {
        if (is_null($this->head))
            throw new InvalidOperationException('no last item');

        return $this->last_node()->data;
    }

    // TODO methods to remove items

    public static function from_array($arr)
    {
        $node = null;

        for ($i = count($arr) - 1; $i >= 0; --$i)
            $node = new ForwardNode($arr[$i], $node);

        $list = new self();
        $list->head = $node;
        
        return $list;
    }

    public function to_array()
    {
        $arr = [];
        foreach ($this->items() as $item)
            $arr[] = $item;
        return $arr;
    }

    public function count() : int
    {
        $count = 0;

        foreach ($this->items() as $_) {
            ++$count;
        }

        return $count;
    }
}
