<?php

require_once 'vendor/autoload.php';

use DataStructures\LinkedList\SinglyLinkedList;

$list = SinglyLinkedList::from_array(['a', 'b', 'c']);

foreach ($list->items() as $item) {
    echo $item, ' ';
}
