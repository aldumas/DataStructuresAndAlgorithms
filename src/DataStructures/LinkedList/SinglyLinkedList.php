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
        foreach ($this->nodes() as $node)
            yield $node->data; // PHP also makes a sequential key available to caller
    }

    private function nodes()
    {
        for ($node = $this->head; !is_null($node); $node = $node->next) {
            yield $node;
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

    public function count_if(callable $predicate) : int
    {
        $count = 0;

        foreach ($this->items() as $item) {
            if ($predicate($item)) ++$count;
        }

        return $count;
    }

    public function count_item($item) : int
    {
        $equals_item = function ($_item) use ($item) { return $_item === $item; };
        return $this->count_if($equals_item);
    }

    public function remove_one($item) : mixed
    {
        if (is_null($this->head))
            return null;

        if ($this->first() === $item) {
            $removed_item = $this->first();
            $this->head = $this->head->next;
            return $removed_item;
        }

        $before_node = $this->find_node_before($item);

        if (is_null($before_node))
            return null;

        $removed_item = $before_node->next->data;
        $before_node->next = $before_node->next->next;

        return $removed_item;
    }

    private function find_node_before($item) : ?ForwardNode
    {
        // there's no node before an empty list or a list with only 1 node
        if (is_null($this->head) || is_null($this->head->next))
            return null;

        $before = $this->head;
        $nodes = $this->nodes(); // Generator
        
        for ($nodes->next(); // move past head so it will not be returned in loop
             $nodes->valid();
             $nodes->next())
        {
            $node = $nodes->current();
            if ($node->data === $item)
                return $before;
            $before = $node;
        }

        return null;
    }
}
