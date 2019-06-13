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
 * Interface for group search results.
 *
 * @api
 */
interface GroupSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get groups list.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface[]
     */
    public function getItems();

    /**
     * Set group list.
     *
     * @param \SolideWebservices\Flexslider\Api\Data\GroupInterface[] $items C.
     *
     * @return $this
     */
    public function setItems(array $items);

}