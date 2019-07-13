<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2019 LYRASOFT.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Utilities\Queue;

/**
 * The PriorityQueue class.
 *
 * @since  2.1.1
 */
class PriorityQueue extends \SplPriorityQueue implements \Serializable
{
    const MIN = -300;

    const LOW = -200;

    const BELOW_NORMAL = -100;

    const NORMAL = 0;

    const ABOVE_NORMAL = 100;

    const HIGH = 200;

    const MAX = 300;

    /**
     * @var int Seed used to ensure queue order for items of the same priority
     */
    protected $serial = PHP_INT_MAX;

    /**
     * Class init.
     *
     * @param array|\SplPriorityQueue $array
     * @param int                     $priority
     */
    public function __construct($array = [], $priority = self::NORMAL)
    {
        if ($array instanceof \SplPriorityQueue) {
            $this->merge($array);
        } else {
            $this->bind($array, $priority);
        }
    }

    /**
     * bind
     *
     * @param array $array
     * @param int   $priority
     *
     * @return  static
     */
    public function bind(array $array = [], $priority = self::NORMAL)
    {
        foreach ($array as $item) {
            $this->insert($item, $priority);
        }

        return $this;
    }

    /**
     * register
     *
     * @param array $items
     *
     * @return  static
     */
    public function insertArray(array $items)
    {
        foreach ($items as $priority => $item) {
            $this->insert($item, $priority);
        }

        return $this;
    }

    /**
     * Insert a value with a given priority
     *
     * Utilizes {@var $serial} to ensure that values of equal priority are
     * emitted in the same order in which they are inserted.
     *
     * @param  mixed $datum
     * @param  mixed $priority
     *
     * @return void
     */
    public function insert($datum, $priority)
    {
        if (!is_array($priority)) {
            $priority = [$priority, $this->serial--];
        } else {
            $priority[] = $this->serial--;
        }

        parent::insert($datum, $priority);
    }

    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach (clone $this as $item) {
            $array[] = $item;
        }

        return $array;
    }

    /**
     * Serialize
     *
     * @return string
     */
    public function serialize()
    {
        $clone = clone $this;

        $clone->setExtractFlags(self::EXTR_BOTH);

        $data = [];

        foreach ($clone as $item) {
            $data[] = $item;
        }

        return serialize($data);
    }

    /**
     * Deserialize
     *
     * @param  string $data
     *
     * @return void
     */
    public function unserialize($data)
    {
        foreach (unserialize($data) as $item) {
            $this->insert($item['data'], $item['priority']);
        }
    }

    /**
     * merge
     *
     * @return static;
     */
    public function merge()
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            if (!($arg instanceof \SplPriorityQueue)) {
                throw new \InvalidArgumentException('Only \SplPriorityQueue can merge.');
            }

            $queue = clone $arg;

            $queue->setExtractFlags(self::EXTR_BOTH);

            foreach ($queue as $item) {
                $this->insert($item['data'], $item['priority']);
            }
        }

        return $this;
    }

    /**
     * compare
     *
     * @param mixed $priority1
     * @param mixed $priority2
     *
     * @return  int
     */
    public function compare($priority1, $priority2)
    {
        $p1Count = count($priority1);
        $p2Count = count($priority2);

        $count = min($p1Count, $p2Count);

        foreach (range(1, $count) as $i) {
            $k = $i - 1;

            if ($priority1[$k] == $priority2[$k]) {
                continue;
            } elseif ($priority1[$k] > $priority2[$k]) {
                return 1;
            } else {
                return -1;
            }
        }

        return 0;
    }

    /**
     * Method to get property Serial
     *
     * @return  int
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Method to set property serial
     *
     * @param   int $serial
     *
     * @return  static  Return self to support chaining.
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }
}
