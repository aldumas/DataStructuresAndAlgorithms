<?php declare(strict_types=1);

use DataStructures\LinkedList\ForwardList;

use PHPUnit\Framework\TestCase;


final class ForwardListTest extends TestCase
{
    public function testCanBeCreatedFromArray() : void
    {
        $arr = ['a', 'b', 'c'];
        $list = ForwardList::from_array($arr);

        $this->assertInstanceOf(ForwardList::class, $list);

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
            ForwardList::class,
            ForwardList::from_array([])
        );
    }

    public function testListCreatedFromEmptyArrayIsItselfEmpty() : void
    {
        $list = ForwardList::from_array([]);
        $this->assertTrue($list->is_empty());
    }

    public function testListCreatedFromNonEmptyArrayIsItselfNotEmpty() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $this->assertFalse($list->is_empty());
    }

    public function testFirstReturnsFirstItemInList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $this->assertEquals('a', $list->first());
    }

    public function testLastReturnsLastItemInList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $this->assertEquals('c', $list->last());
    }

    public function testCanAppendItemToEmptyList() : void
    {
        $list = new ForwardList();

        $list->append(42);
        $this->assertEquals(42, $list->first());
    }

    public function testAppendingToListAddsToEnd() : void
    {
        $list = ForwardList::from_array(['a', 'b']);

        $list->append('c');
        $this->assertEquals('c', $list->last());
    }

    public function testFirstAndLastReturnSameItemInOneItemList() : void
    {
        $list = ForwardList::from_array([42]);
        $this->assertEquals($list->first(), $list->last());
    }

    public function testCanPrependItemToEmptyList() : void
    {
        $list = ForwardList::from_array([]);
        $list->prepend('a');

        $this->assertEquals('a', $list->first());
    }

    public function testPrependingToNonEmptyListSetsItemAsFirst() : void
    {
        $list = ForwardList::from_array(['b', 'c']);
        $list->prepend('a');

        $this->assertEquals('a', $list->first());
    }

    public function testPrependingToNonEmptyListDoesNotDiscardRestOfList(): void
    {
        $list = ForwardList::from_array(['b', 'c']);
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
        $list = ForwardList::from_array($arr);

        $this->assertEquals($arr, $list->to_array());
    }

    public function testCanCreateAnArrayFromEmptyList(): void
    {
        $arr = [];
        $list = ForwardList::from_array($arr);

        $this->assertEquals($arr, $list->to_array());
    }

    public function testCountReturnsZeroForEmptyList() : void
    {
        $list = new ForwardList();

        $this->assertEquals(0, $list->count());
    }

    public function testCountReturnsCorrectCountForNonEmptyList() : void
    {
        $arr = ['a', 'b', 'c'];
        $list = ForwardList::from_array($arr);

        $this->assertEquals(count($arr), $list->count());
    }

    public function testCountItemReturnsCorrectCount() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'b', 'c']);
        
        $this->assertEquals(2, $list->count_item('b'));
    }

    public function testCountIfReturnsCorrectCount() : void
    {
        $arr = [0, 1, 2, 3, 4];
        $list = ForwardList::from_array($arr);

        $is_odd = function ($item) { return $item % 2; };

        $this->assertEquals(2, $list->count_if($is_odd));
    }

    public function testRemoveOneRemovesExactlyOneIfItemFound() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'b', 'c']);
        $item = $list->remove_one('b');

        $this->assertEquals('b', $item);
        $this->assertEquals(1, $list->count_item('b'));
    }

    public function testRemoveOneCanRemoveFirstItem() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('a');

        $this->assertEquals('a', $item);
        $this->assertEquals('b', $list->first());
    }

    public function testRemoveOneCanRemoveLastItem() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('c');

        $this->assertEquals('c', $item);
        $this->assertEquals('b', $list->last());
    }

    public function testRemoveOneReturnsNullIfItemNotFound() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $item = $list->remove_one('d');

        $this->assertNull($item);
    }

    public function testRemoveOneReturnsNullIfListIsEmpty() : void
    {
        $list = new ForwardList();
        $item = $list->remove_one('a');

        $this->assertNull($item);
    }

    public function testRemoveAllReturnsZeroIfNoMatchingItems() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);
        $removed_count = $list->remove_all('d');

        $this->assertEquals(0, $removed_count);
    }

    public function testRemoveAllReturnsNumberOfRemovedItems() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c', 'b', 'd']);
        $removed_count = $list->remove_all('b');

        $this->assertEquals(2, $removed_count);
    }

    public function testRemoveAllCanRemoveItemsFromStartAndEndOfList() : void
    {
        $list = ForwardList::from_array(['a', 'a', 'b', 'a', 'a', 'c', 'a', 'a']);
        $removed_count = $list->remove_all('a');

        $this->assertEquals(6, $removed_count);
        $this->assertEquals(['b', 'c'], $list->to_array());
    }

    public function testInsertBeforeDoesNotInsertIntoEmptyList() : void
    {
        $list = new ForwardList();
        $did_insert = $list->insert_before('existing item', 'item to insert');

        $this->assertFalse($did_insert);
        $this->assertTrue($list->is_empty());
    }

    public function testInsertBeforeCanInsertAtStartOfList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);

        $did_insert = $list->insert_before('a', 'x');

        $this->assertTrue($did_insert);
        this->assertEquals(['x', 'a', 'b', 'c'], $this->to_array());
    }

    public function testInsertBeforeLastNodeOfList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);

        $did_insert = $list->insert_before('c', 'x');

        $this->assertTrue($did_insert);
        $this->assertEquals(['a', 'b', 'x', 'c'], $this->to_array());
    }

    public function testInsertAfterDoesNotInsertIntoEmptyList() : void
    {
        $list = new ForwardList();
        $did_insert = $list->insert_after('existing item', 'item to insert');

        $this->assertFalse($did_insert);
        $this->assertTrue($list->is_empty());
    }

    public function testInsertAfterCanInsertAfterStartOfList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);

        $did_insert = $list->insert_after('a', 'x');

        $this->assertTrue($did_insert);
        $this->assertEquals(['a', 'x', 'b', 'c'], $this->to_array());
    }

    public function testInsertAfterCanInsertAfterEndOfList() : void
    {
        $list = ForwardList::from_array(['a', 'b', 'c']);

        $did_insert = $list->insert_after('c', 'x');

        $this->assertTrue($did_insert);
        $this->assertEquals(['a', 'b', 'c', 'x'], $this->to_array());
    }
}
