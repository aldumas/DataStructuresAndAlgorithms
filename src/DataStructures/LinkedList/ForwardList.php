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
        if (is_null($this->head))
            return null;

        if ($this->first() === $item) {
            $removed_item = $this->first();
            $this->head = $this->head->next;
            return $removed_item;
        }

        $start = $this->find_node_before($item, $this->head);

        if (is_null($start))
            return null;

        $removed_item = $start->next->data;
        $start->next = $start->next->next;

        return $removed_item;
    }

    // Find the first node after $start which has $item and return the node
    // before it
    private function find_node_before($item, ?ForwardNode $start) : ?ForwardNode
    {
        // $start is the first possible node we could return
        if (is_null($start) || is_null($start->next))
            return null;

        $before = $start;

        for ($node = $start->next; !is_null($node); $node = $node->next) {
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
        $start = $faux_head;

        while (true) {
            $start = $this->find_node_before($item, $start);

            if (is_null($start)) break;

            ++$count;
            $start->next = $start->next->next;
        }

        // Update head since the previous one may have been removed.
        $this->head = $faux_head->next;

        return $count;
    }
}
