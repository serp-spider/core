<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class ItemPosition extends ProxyResult
{

    protected $positionOnPage;
    protected $realPosition;
    protected $serpFeaturePositionOnPage;

    /**
     * @return false
     */
    public function getSerpFeaturePositionOnPage() {
        if($this->serpFeatureHasSidePosition() && $this->serpFeaturePositionOnPage) {
            return 'side';
        }
        return $this->serpFeaturePositionOnPage;
    }

    /**
     * @param false $serpFeaturePositionOnPage
     */
    public function setSerpFeaturePositionOnPage(int $serpFeaturePositionOnPage): void {
        $this->serpFeaturePositionOnPage = $serpFeaturePositionOnPage;
    }

    /**
     * ItemPosition constructor.
     * @param int $positionOnPage
     * @param int $realPosition
     * @param ResultDataInterface $itemData
     */
    public function __construct($positionOnPage, $realPosition, ResultDataInterface $itemData)
    {
        $this->positionOnPage = $positionOnPage;
        $this->realPosition = $realPosition;

        parent::__construct($itemData);
    }

    /**
     * @return int the position of the item on the page (starting at 1)
     */
    public function getOnPagePosition()
    {
        return $this->positionOnPage;
    }

    /**
     * @return int the general position of the item (starting at 1)
     */
    public function getRealPosition()
    {
        return $this->realPosition;
    }
}
