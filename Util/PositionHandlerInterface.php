<?php

namespace Skillberto\SonataPageMenuBundle\Util;

interface PositionHandlerInterface
{
    /**
     * @param  array $positions
     *
     * @return $this
     */
    public function setPositions(array $positions);

    /**
     * @param  int      $id
     * @param  mixed    $value
     *
     * @return $this
     */
    public function setPosition($id, $value);

    /**
     * @param  array $lastPositions
     *
     * @return $this
     */
    public function setLastPositions(array $lastPositions);

    /**
     * @return array
     */
    public function getPositions();

    /**
     * @param  int $id
     *
     * @return int
     */
    public function getPosition($id);

    /**
     * @return array
     */
    public function getLastPositions();

    /**
     * @param  int $id
     *
     * @return int
     */
    public function getLastPosition($id);


    /**
     * @param  int $id
     *
     * @return $this
     */
    public function incrementPosition($id);
}