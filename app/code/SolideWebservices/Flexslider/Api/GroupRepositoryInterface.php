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

namespace SolideWebservices\Flexslider\Api;

use Magento\Framework\Api\SearchCriteriaInterface as GroupSearchCriteria;
use SolideWebservices\Flexslider\Api\Data\GroupInterface;

/**
 * Group CRUD interface.
 *
 * @api
 */
interface GroupRepositoryInterface
{
    /**
     * Save group.
     *
     * @param GroupInterface $group Group data.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function save(GroupInterface $group);

    /**
     * Retrieve group.
     *
     * @param int $groupId Group id.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\GroupInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function getById($groupId);

    /**
     * Retrieve groups matching the specified criteria.
     *
     * @param GroupSearchCriteria $searchCriteria Group search criteria.
     *
     * @return \SolideWebservices\...\Api\Data\GroupSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function getList(GroupSearchCriteria $searchCriteria);

    /**
     * Delete group.
     *
     * @param GroupInterface $group Group data.
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function delete(GroupInterface $group);

    /**
     * Delete group by ID.
     *
     * @param int $groupId Group id.
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException No group.
     * @throws \Magento\Framework\Exception\LocalizedException    Default ex.
     */
    public function deleteById($groupId);

}
