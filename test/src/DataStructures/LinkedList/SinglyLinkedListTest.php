<?php declare(strict_types=1);

use DataStructures\LinkedList\SinglyLinkedList;

use PHPUnit\Framework\TestCase;


final class SinglyLinkedListTest extends TestCase
{
    public function testCanBeCreatedFromArray() : void
    {
        $arr = ['a', 'b', 'c'];
        $list = SinglyLinkedList::from_array($arr);

        $this->assertInstanceOf(SinglyLinkedList::class, $list);

        $expected = $arr;
        $actual = [];
        foreach ($list->items() as $item) {
            $actual[] = $item;
        }

        $this->assertEquals($expected, $actual);
    }

    public function testCanBeCreatedFromEmptyArray() : void
    {
        $this->assertInstanceOf(
            SinglyLinkedList::class,
            SinglyLinkedList::from_array([])
        );
    }

    public function testListCreatedFromEmptyArrayIsItselfEmpty() : void
    {
        $list = SinglyLinkedList::from_array([]);
        $this->assertTrue($list->is_empty());
    }

    public function testListCreatedFromNonEmptyArrayIsItselfNotEmpty() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $this->assertFalse($list->is_empty());
    }

    public function testFirstReturnsFirstItemInList() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $this->assertEquals('a', $list->first());
    }

    public function testLastReturnsLastItemInList() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $this->assertEquals('c', $list->last());
    }

    public function testCanAppendItemToEmptyList() : void
    {
        $list = new SinglyLinkedList();

        $list->append(42);
        $this->assertEquals(42, $list->first());
    }

    public function testAppendingToListAddsToEnd() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b']);

        $list->append('c');
        $this->assertEquals('c', $list->last());
    }

    public function testFirstAndLastReturnSameItemInOneItemList() : void
    {
        $list = SinglyLinkedList::from_array([42]);
        $this->assertEquals($list->first(), $list->last());
    }

    public function testCanPrependItemToEmptyList() : void
    {
        $list = SinglyLinkedList::from_array([]);
        $list->prepend('a');

        $this->assertEquals('a', $list->first());
    }

    public function testPrependingToNonEmptyListSetsItemAsFirst() : void
    {
        $list = SinglyLinkedList::from_array(['b', 'c']);
        $list->prepend('a');

        $this->assertEquals('a', $list->first());
    }

    public function testPrependingToNonEmptyListDoesNotDiscardRestOfList(): void
    {
        $list = SinglyLinkedList::from_array(['b', 'c']);
        $list->prepend('a');

        $expected = ['a', 'b', 'c'];

        $actual = [];
        foreach ($list->items() as $item) {
            $actual[] = $item;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testCanCreateAnArrayFromNonEmptyList(): void
    {
        $arr = ['a', 'b', 'c'];
        $list = SinglyLinkedList::from_array($arr);

        $this->assertEquals($arr, $list->to_array());
    }

    public function testCanCreateAnArrayFromEmptyList(): void
    {
        $arr = [];
        $list = SinglyLinkedList::from_array($arr);

        $this->assertEquals($arr, $list->to_array());
    }

    public function testCountReturnsZeroForEmptyList() : void
    {
        $list = new SinglyLinkedList();

        $this->assertEquals(0, $list->count());
    }

    public function testCountReturnsCorrectCountForNonEmptyList() : void
    {
        $arr = ['a', 'b', 'c'];
        $list = SinglyLinkedList::from_array($arr);

        $this->assertEquals(count($arr), $list->count());
    }

    public function testCountItemReturnsCorrectCount() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'b', 'c']);
        
        $this->assertEquals(2, $list->count_item('b'));
    }

    public function testCountIfReturnsCorrectCount() : void
    {
        $arr = [0, 1, 2, 3, 4];
        $list = SinglyLinkedList::from_array($arr);

        $is_odd = function ($item) { return $item % 2; };

        $this->assertEquals(2, $list->count_if($is_odd));
    }

    public function testRemoveOneRemovesExactlyOneIfItemFound() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'b', 'c']);
        $item = $list->remove_one('b');

        $this->assertEquals('b', $item);
        $this->assertEquals(1, $list->count_item('b'));
    }

    public function testRemoveOneCanRemoveFirstItem() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('a');

        $this->assertEquals('a', $item);
        $this->assertEquals('b', $list->first());
    }

    public function testRemoveOneCanRemoveLastItem() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('c');

        $this->assertEquals('c', $item);
        $this->assertEquals('b', $list->last());
    }

    public function testRemoveOneReturnsNullIfItemNotFound() : void
    {
        $list = SinglyLinkedList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('d');

        $this->assertNull($item);
    }

    public function testRemoveOneReturnsNullIfListIsEmpty() : void
    {
        $list = new SinglyLinkedList();
        $item = $list->remove_one('a');

        $this->assertNull($item);
    }
}
