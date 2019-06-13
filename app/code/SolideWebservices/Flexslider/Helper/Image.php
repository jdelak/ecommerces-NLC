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

namespace SolideWebservices\Flexslider\Helper;

use SolideWebservices\Flexslider\Model\Slide;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\File\Size;
use Magento\Framework\HTTP\Adapter\FileTransferFactory;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Image\Factory;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Flexslider Image Helper
 */
class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DEFAULT_MEDIA_PATH = Slide::DEFAULT_MEDIA_PATH;
    const THUMB_MEDIA_PATH   = Slide::THUMB_MEDIA_PATH;
    const SMALL_MEDIA_PATH   = Slide::SMALL_MEDIA_PATH;
    const MEDIUM_MEDIA_PATH  = Slide::MEDIUM_MEDIA_PATH;
    const LARGE_MEDIA_PATH   = Slide::LARGE_MEDIA_PATH;
    const MAX_FILE_SIZE      = 2000000;
    const MIN_HEIGHT         = 50;
    const MAX_HEIGHT         = 1200;
    const MIN_WIDTH          = 50;
    const MAX_WIDTH          = 3000;

    /**
     * Variable.
     *
     * @var inherit
     */
    protected $_scopeConfig;

    /**
     * Variable.
     *
     * @var imagesizes
     */
    protected $_imageSize   = [
        'minheight' => self::MIN_HEIGHT,
        'minwidth'  => self::MIN_WIDTH,
        'maxheight' => self::MAX_HEIGHT,
        'maxwidth'  => self::MAX_WIDTH,
    ];

    /**
     * Variable.
     *
     * @var mediadirectory
     */
    protected $mediaDirectory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * Variable.
     *
     * @var \Magento\Framework\HTTP\Adapter\FileTransferFactory
     */
    protected $httpFactory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $_ioFile;

    /**
     * Variable.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct.
     *
     * @param Context               $context             Context.
     * @param Filesystem            $filesystem          FileSystem.
     * @param Size                  $fileSize            FileSize.
     * @param FileTransferFactory   $httpFactory         HTTPFactory.
     * @param UploaderFactory       $fileUploaderFactory FileUploaderFactory.
     * @param File                  $ioFile              IOFile.
     * @param StoreManagerInterface $storeManager        StoreManager.
     * @param Factory               $imageFactory        ImageFactory.
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Size $fileSize,
        FileTransferFactory $httpFactory,
        UploaderFactory $fileUploaderFactory,
        File $ioFile,
        StoreManagerInterface $storeManager,
        Factory $imageFactory
    ) {
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * Get store config.
     *
     * @param string $path  Path.
     * @param int    $store Store.
     *
     * @return string
     */
    public function getConfig($path, $store = null)
    {
        return $this->_scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Delete image file.
     *
     * @param string $image Image.
     *
     * @return bool
     */
    public function removeImage($image)
    {
        $imageUrl     = $this->getImagePaths(self::DEFAULT_MEDIA_PATH) . $image;
        $thumbResized  = $this->getImagePaths(self::THUMB_MEDIA_PATH) . $image;
        $smallResized  = $this->getImagePaths(self::SMALL_MEDIA_PATH) . $image;
        $mediumResized = $this->getImagePaths(self::MEDIUM_MEDIA_PATH) . $image;
        $largeResized  = $this->getImagePaths(self::LARGE_MEDIA_PATH) . $image;

        $io = $this->_ioFile;
        $io->open(['path' => $this->getBaseDir()]);
        if ($io->fileExists($imageUrl)) {

            $io->rm($thumbResized );
            $io->rm($smallResized);
            $io->rm($mediumResized);
            $io->rm($largeResized);

            return $io->rm($imageUrl);
        }
        return false;
    }

    /**
     * Upload image file.
     *
     * @param string $image Image.
     *
     * @return bool
     */
    public function uploadImage($image)
    {
        $adapter = $this->httpFactory->create();
        $adapter->addValidator(new \Zend_Validate_File_ImageSize(
            $this->_imageSize));
        $adapter->addValidator(new \Zend_Validate_File_FilesSize(
            ['max' => self::MAX_FILE_SIZE]));

        if ($adapter->isUploaded($image)) {
            if (!$adapter->isValid($image)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Uploaded image is not valid.')
                );
            }

            $uploader = $this->_fileUploaderFactory
                ->create(['fileId' => $image]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->setAllowCreateFolders(true);
            $uploader->save($this->getBaseDir());

            $this->createDifferentImageSizes($uploader->getUploadedFileName());

            return $uploader->getUploadedFileName();

        }
        return false;
    }

    /**
     * Create different image sizes.
     *
     * @param string $image Image.
     *
     * @return void
     */
    public function createDifferentImageSizes($image)
    {
        $imageUrl     = $this->getImagePaths(self::DEFAULT_MEDIA_PATH) . $image;
        $thumbResized  = $this->getImagePaths(self::THUMB_MEDIA_PATH) . $image;
        $smallResized  = $this->getImagePaths(self::SMALL_MEDIA_PATH) . $image;
        $mediumResized = $this->getImagePaths(self::MEDIUM_MEDIA_PATH) . $image;
        $largeResized  = $this->getImagePaths(self::LARGE_MEDIA_PATH) . $image;
        $size          = $this->getConfig('flexslider/general/thumbnail_upload_width');

        if (file_exists($imageUrl)) :
            if (!file_exists($thumbResized)) :
                $thumbObj = $this->_imageFactory->create($imageUrl);
                $thumbObj->constrainOnly(TRUE);
                $thumbObj->keepAspectRatio(TRUE);
                $thumbObj->keepFrame(FALSE);
                $thumbObj->resize($size, null);
                $thumbObj->save($thumbResized);
            endif;

            if (!file_exists($smallResized)) :
                $smallObj = $this->_imageFactory->create($imageUrl);
                $smallObj->constrainOnly(TRUE);
                $smallObj->keepAspectRatio(TRUE);
                $smallObj->keepFrame(FALSE);
                $smallObj->resize(320, null);
                $smallObj->save($smallResized);
            endif;

            if (!file_exists($mediumResized)) :
                $mediumObj = $this->_imageFactory->create($imageUrl);
                $mediumObj->constrainOnly(TRUE);
                $mediumObj->keepAspectRatio(TRUE);
                $mediumObj->keepFrame(FALSE);
                $mediumObj->resize(640, null);
                $mediumObj->save($mediumResized);
            endif;

            if (!file_exists($largeResized)) :
                $largeObj = $this->_imageFactory->create($imageUrl);
                $largeObj->constrainOnly(TRUE);
                $largeObj->keepAspectRatio(TRUE);
                $largeObj->keepFrame(FALSE);
                $largeObj->resize(1024, null);
                $largeObj->save($largeResized);
            endif;
        endif;

    }

    /**
     * Resize images.
     *
     * @param \SolideWebservices\Flexslider\Model\Slide $item   Item.
     * @param int                                       $width  Width.
     * @param int                                       $height Height.
     *
     * @return bool
     */
    public function resize(
        \SolideWebservices\Flexslider\Model\Slide $item,
        $width,
        $height = null
    ) {
        if (!$item->getImage()) {
            return false;
        }
        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }
        $width = (int)$width;
        if ($height != null) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }
            $height = (int)$height;
        }
        $imageFile = $item->getImage();
        $cacheDir  = $this->getBaseDir() . '/' . 'cache' . '/' . $width;
        $cacheUrl  = $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';
        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(['path' => $cacheDir]);
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }
        try {
            $image = $this->_imageFactory
                ->create($this->getBaseDir() . '/' . $imageFile);
            $image->resize($width, $height);
            $image->save($cacheDir . '/' . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the absolute path.
     *
     * @param string $mediapath Mediapath.
     *
     * @return string
     */
    public function getBaseDir($mediapath = self::DEFAULT_MEDIA_PATH)
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($mediapath);
    }

    /**
     * Get the base url.
     *
     * @param string $mediapath Mediapath.
     *
     * @return string
     */
    public function getBaseUrl($mediapath = self::DEFAULT_MEDIA_PATH)
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $mediapath;
    }

    /**
     * Get the image url.
     *
     * @param string $image     Image.
     * @param string $mediapath Mediapath.
     *
     * @return string || null
     */
    public function getImageUrl($image, $mediapath = self::DEFAULT_MEDIA_PATH)
    {
        if ($this->imageExists($image, $mediapath)) {
            return $this->getBaseUrl($mediapath) . $image;
        }
        return null;
    }

    /**
     * Get the image path.
     *
     * @param string $mediapath Mediapath.
     *
     * @return string
     */
    public function getImagePaths($mediapath = self::DEFAULT_MEDIA_PATH)
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($mediapath);
    }

    /**
     * Chech if image exists.
     *
     * @param string $image     Image.
     * @param string $mediapath Mediapath.
     *
     * @return bool
     */
    public function imageExists($image, $mediapath = self::DEFAULT_MEDIA_PATH)
    {
        return is_file($this->getImagePaths($mediapath) . $image);
    }


}
