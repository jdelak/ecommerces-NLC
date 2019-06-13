<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * interface for slide search results
 *
 * @api
 */
interface SlideSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get slides list.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface[]
     */
    public function getItems();

    /**
     * Set slide list.
     *
     * @param \SolideWebservices\Flexslider\Api\Data\SlideInterface[] $items C.
     *
     * @return $this
     */
    public function setItems(array $items);

}
