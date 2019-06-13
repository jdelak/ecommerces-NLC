<?php
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$assetRepository = \Magento\Framework\View\Asset\Repository;
$asset = $assetRepository->createAsset('Ntic_Timescan::web/images/clocks');
?>