<?php


namespace Skillberto\SonataPageMenuBundle\Util;


class PositionHandler
{
    protected
        $positions = array(),
        $lastPositions = array()
    ;

    /**
     * @param  array $positions
     *
     * @return $this
     */
    public function setPositions(array $positions)
    {
        $this->positions = $positions;

        return $this;
    }

    /**
     * @param int $id
     * @param $value
     *
     * @return $this
     */
    public function setPosition($id, $value)
    {
        $this->positions[$id] = $value;

        return $this;
    }

    /**
     * @param  array $lastPositions
     *
     * @return $this
     */
    public function setLastPositions(array $lastPositions)
    {
        $this->lastPositions = $lastPositions;

        return $this;
    }

    /**
     * @return int
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param  int $id
     *
     * @return int
     */
    public function getPosition($id)
    {
        return isset($this->positions[$id]) ? $this->positions[$id] : 0;
    }

    /**
     * @return array
     */
    public function getLastPositions()
    {
        return $this->lastPositions;
    }

    /**
     * @param  int $id
     *
     * @return int
     */
    public function getLastPosition($id)
    {
        return isset($this->lastPositions[$id]) ? $this->lastPositions[$id] : 0;
    }


    /**
     * @param  int $id
     *
     * @return $this
     */
    public function incrementPosition($id)
    {
        $value = $this->getPosition($id);

        $this->setPosition($id, $value + 1);

        return $this;
    }
}