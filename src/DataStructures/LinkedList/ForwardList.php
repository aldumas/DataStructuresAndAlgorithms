<?php

namespace DataStructures\LinkedList;

// A singly-linked list implementation where each node points forward to the
// next node in the list.
class ForwardList
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
        for ($node = $this->head; !is_null($node); $node = $node->next)
            yield $node->data; // PHP also makes a sequential key available to caller
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
        // So we don't need to treat the head node as special, we add one.
        $faux_head = new ForwardNode(null, $this->head);

        $before = $this->find_node_before($item, $faux_head);

        if (is_null($before)) {
            return null;
        }

        $data = $before->next->data;
        $before->next = $before->next->next;
        
        // Update head since the previous one may have been removed.
        $this->head = $faux_head->next;

        return $data;
    }

    // Find the first node after $before which has $item and return the node
    // before it
    private function find_node_before($item, ?ForwardNode $before) : ?ForwardNode
    {
        // $before is the first possible node we could return
        if (is_null($before) || is_null($before->next))
            return null;

        $before = $before;

        for ($node = $before->next; !is_null($node); $node = $node->next) {
            if ($node->data === $item)
                return $before;

            $before = $node;
        }

        return null;
    }

    public function remove_all($item) : int
    {
        // So we don't need to treat the head node as special, we add one.
        $faux_head = new ForwardNode(null, $this->head);

        $count = 0;
        $before = $faux_head;

        while (true) {
            $before = $this->find_node_before($item, $before);

            if (is_null($before)) break;

            ++$count;
            $before->next = $before->next->next;
        }

        // Update head since the previous one may have been removed.
        $this->head = $faux_head->next;

        return $count;
    }

    public function insert_before($needle, $item) : bool
    {
        return false; // TODO
    }

    public function insert_after($needle, $item) : bool
    {
        return false; // TODO
    }
}
