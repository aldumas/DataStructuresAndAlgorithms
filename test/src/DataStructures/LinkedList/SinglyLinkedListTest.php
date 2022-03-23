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
}
