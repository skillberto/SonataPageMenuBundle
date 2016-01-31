<?php


namespace Skillberto\SonataPageMenuBundle\Util;


class PositionHandler implements PositionHandlerInterface
{
    protected
        $positions = array(),
        $lastPositions = array()
    ;

    /**
     * {@inheritdoc}
     */
    public function setPositions(array $positions)
    {
        $this->positions = $positions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($id, $value)
    {
        $this->positions[$id] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastPositions(array $lastPositions)
    {
        $this->lastPositions = $lastPositions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition($id)
    {
        return isset($this->positions[$id]) ? $this->positions[$id] : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPositions()
    {
        return $this->lastPositions;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPosition($id)
    {
        return isset($this->lastPositions[$id]) ? $this->lastPositions[$id] : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementPosition($id)
    {
        $value = $this->getPosition($id);

        $this->setPosition($id, $value + 1);

        return $this;
    }
}