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

use Magento\Framework\Api\SearchCriteriaInterface as SlideSearchCriteria;
use SolideWebservices\Flexslider\Api\Data\SlideInterface;

/**
 * group CRUD interface.
 *
 * @api
 */
interface SlideRepositoryInterface
{
    /**
     * Save slide.
     *
     * @param SlideInterface $slide Slide data.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default exception.
     */
    public function save(SlideInterface $slide);

    /**
     * Retrieve slide.
     *
     * @param int $slideId Slide id.
     *
     * @return \SolideWebservices\Flexslider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function getById($slideId);

    /**
     * Retrieve slides matching the specified criteria.
     *
     * @param SlideSearchCriteria $searchCriteria Slide search criteria.
     *
     * @return \SolideWebservices\...\Api\Data\SlideSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function getList(SlideSearchCriteria $searchCriteria);

    /**
     * Delete slide.
     *
     * @param SlideInterface $slide Slide data.
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException Default except.
     */
    public function delete(SlideInterface $slide);

    /**
     * Delete slide by ID.
     *
     * @param int $slideId Slide id.
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException No slide.
     * @throws \Magento\Framework\Exception\LocalizedException    Default ex.
     */
    public function deleteById($slideId);

}
