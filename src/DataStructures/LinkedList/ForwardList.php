<?php

namespace DataStructures\LinkedList;


/**
 * A singly-linked list where each node points forward to the next node in the
 * list.
 */
class ForwardList
{
    /**
     * Return a list created from an indexed array.
     * 
     * The elements of the array will appear in the list in the order
     * determined by their keys, not PHP's internal array offsets.
     * 
     * @param array $arr indexed array with consecutive keys in the range
     *   [0, n) where n is the number of elements in the array.
     * 
     * @return ForwardList
     */
    public static function from_array($arr)
    {
        $node = null;

        for ($i = count($arr) - 1; $i >= 0; --$i)
            $node = new ForwardNode($arr[$i], $node);

        $list = new self();
        $list->head = $node;
        
        return $list;
    }

    /**
     * Add an item to the end of the list.
     *
     * @param mixed $item value to append
     * @return void
     */
    public function append($item)
    {
        $node = new ForwardNode($item);

        if (is_null($this->head))
            $this->head = $node;
        else
            $this->last_node()->next = $node;
    }

    /**
     * Return the number of items in the list.
     *
     * @return integer
     */
    public function count() : int
    {
        $count = 0;

        foreach ($this->items() as $_)
            ++$count;

        return $count;
    }

    /**
     * Return the number of items in the list which match the given predicate.
     *
     * @param callable $predicate function($item) : bool, receives an item from
     *   the list and returns true if it should be counted.
     * @return integer
     */
    public function count_if(callable $predicate) : int
    {
        $count = 0;

        foreach ($this->items() as $item)
            if ($predicate($item)) ++$count;

        return $count;
    }

    /**
     * Return the number of times the given item occurs in the list.
     * 
     * Strict equality is used for the comparison.
     *
     * @param mixed $item
     * @return integer
     */
    public function count_item($item) : int
    {
        $equals_item = function ($_item) use ($item) { return $_item === $item; };
        return $this->count_if($equals_item);
    }

    /**
     * Return the first item in the list.
     *
     * @throw InvalidOperationException if the list is empty.
     * @return mixed
     */
    public function first()
    {
        if (is_null($this->head))
            throw new InvalidOperationException('no first item');

        return $this->head->data;
    }

    /**
     * Insert a given item after the first occurrence of another item (the
     * needle) in the list.
     * 
     * If the needle is not found in the list, the item will not be inserted.
     * 
     * Strict equality is used for comparisons.
     *
     * @param mixed $needle item after which to insert
     * @param mixed $item item to insert
     * @return boolean true if the item was inserted
     */
    public function insert_after($needle, $item) : bool
    {
        $needle_node = $this->find_node($needle);

        if (is_null($needle_node))
            return false;

        $node = new ForwardNode($item, $needle_node->next);
        $needle_node->next = $node;

        return true;
    }

    /**
     * Insert a given item before the first occurrence of another item (the
     * needle) in the list.
     * 
     * If the needle is not found in the list, the item will not be inserted.
     * 
     * Strict equality is used for comparisons.
     * 
     * @param mixed $needle item before which to insert
     * @param mixed $item item to insert
     * @return boolean true if the item was inserted
     */
    public function insert_before($needle, $item) : bool
    {
        // So we don't need to treat the head node as special, we add one.
        $faux_head = new ForwardNode(null, $this->head);

        $before = $this->find_node_before($needle, $faux_head);

        if (is_null($before))
            return false;

        $node = new ForwardNode($item, $before->next);
        $before->next = $node;

        // Update head since the new node may have been inserted there
        $this->head = $faux_head->next;

        return true;
    }

    /**
     * Return true if there are no items in the list.
     *
     * @return boolean
     */
    public function is_empty()
    {
        return is_null($this->head);
    }

    /**
     * Return a generator which iterates over all the items in the list.
     *
     * @return Generator
     */
    public function items()
    {
        for ($node = $this->head; !is_null($node); $node = $node->next)
            yield $node->data; // PHP also provides sequential key ($k => $v)
    }

    /**
     * Return the last item in the list.
     *
     * @throw InvalidOperationException if the list is empty.
     * @return mixed
     */
    public function last()
    {
        if (is_null($this->head))
            throw new InvalidOperationException('no last item');

        return $this->last_node()->data;
    }

    /**
     * Add an item to the beginning of the list.
     *
     * @param mixed $item value to prepend
     * @return void
     */
    public function prepend($item)
    {
        $this->head = new ForwardNode($item, $this->head);
    }

    /**
     * Remove all occurrences of an item in the list.
     * 
     * Strict equality is used for comparisons.
     *
     * @param mixed $item
     * @return integer number of items removed
     */
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

    /**
     * Remove the first occurrence of an item in the list.
     * 
     * Strict equality is used for comparisons.
     *
     * @param mixed $item
     * @return mixed the removed item
     */
    public function remove_one($item) : mixed
    {
        // So we don't need to treat the head node as special, we add one.
        $faux_head = new ForwardNode(null, $this->head);

        $before = $this->find_node_before($item, $faux_head);

        if (is_null($before))
            return null;

        $data = $before->next->data;
        $before->next = $before->next->next;
        
        // Update head since the previous one may have been removed.
        $this->head = $faux_head->next;

        return $data;
    }

    /**
     * Return an array created from the list.
     *
     * @return array
     */
    public function to_array()
    {
        $arr = [];
        foreach ($this->items() as $item)
            $arr[] = $item;
        return $arr;
    }

    /**
     * Return the first node in the list which contains the given item.
     * 
     * Strict equality is used for comparisons.
     *
     * @internal
     * 
     * @param mixed $item
     * @return ForwardNode|null
     */
    private function find_node($item)
    {
        for ($node = $this->head; !is_null($node); $node = $node->next) {
            if ($node->data === $item)
                return $node;
        }
        return null;
    }

    /**
     * Return the node before the first node which contains a given item.
     * 
     * If the first item in the list contains the given item, null will still be
     * returned, since there is no node before it. If that behavior is
     * undesirable, you can either handle that case separately, or prepend the
     * list with another node.
     * 
     * @internal
     * 
     * @todo make $before required, since we just return null immediately anyway.
     *
     * @param mixed $item
     * @param ForwardNode|null $before the node after which to begin the search.
     *   If the node immediately after $before contains the item, then $before
     *   will be returned.
     * @return ForwardNode|null
     */
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

    /**
     * Return the last node in the list.
     *
     * @internal 
     * 
     * @return ForwardNode|null
     */
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

    /**
     * The first node in the list.
     *
     * @internal
     * 
     * @var ForwardNode
     */
    private $head = null;
}
